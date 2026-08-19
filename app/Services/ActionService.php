<?php

namespace App\Services;

use App\Enums\VitalType;
use App\Interfaces\Buyable;
use App\Interfaces\Sellable;
use App\Models\Component;
use App\Models\LabProduction;
use App\Models\User;
use App\Models\UserEquipment;
use App\Models\UserFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActionService
{
    public function __construct(protected User $user) {}

    public function deposit(int $amount): int
    {
        if ($amount <= 0 || $this->user->cash < $amount) {
            throw new \RuntimeException('Você não possui dinheiro para depositar!');
        }

        DB::transaction(function () use ($amount) {
            $this->user->adjustCash(-$amount);
            $this->user->increment('bank', $amount);
        });

        return $amount;
    }

    public function withdraw(int $amount): int
    {
        if ($amount <= 0 || $this->user->bank < $amount) {
            throw new \RuntimeException('Você não possui saldo no banco para sacar!');
        }

        DB::transaction(function () use ($amount) {
            $this->user->adjustCash($amount);
            $this->user->decrement('bank', $amount);
        });

        return $amount;
    }

    public function buy(Buyable $item, int $quantity = 1): int
    {
        return DB::transaction(function () use ($item, $quantity) {
            $cost = $item->getPrice() * $quantity;

            $this->user->validateFunds($cost);
            $this->user->adjustCash(-$cost);

            $item->addToUser($this->user, $quantity);

            return $cost;
        });
    }

    public function sell(Sellable $item, int $quantity = 1): int
    {
        return DB::transaction(function () use ($item, $quantity) {
            $item->validateInventory($this->user, $quantity);

            // sell price is only half of buy price
            $profit = (int) floor(($item->getPrice() * $quantity) / 2);

            $this->user->adjustCash($profit);
            $item->removeFromUser($this->user, $quantity);

            if ($item instanceof \App\Models\Drug) {
                $this->user->increment('drug_profits', $profit);
            }

            return $profit;
        });
    }

    public function activateEquipment(UserEquipment $userEquipment): void
    {
        DB::transaction(function () use ($userEquipment) {
            $user = $this->user;
            $type = $userEquipment->equipment->type;

            // Deactivate all user equipment of same type
            $userEqIds = DB::table('user_equipment')
                ->join('equipment', 'equipment.id', '=', 'user_equipment.equipment_id')
                ->where('user_equipment.user_id', $user->id)
                ->where('equipment.type', $type)
                ->pluck('user_equipment.id');

            DB::table('user_equipment')->whereIn('id', $userEqIds)->update(['active' => false]);

            // Set target equipment as active
            $userEquipment->active = true;
            $userEquipment->save();

            if ($type === 'armor') {
                $user->armor_id = $userEquipment->equipment_id;
            } else {
                $user->weapon_id = $userEquipment->equipment_id;
            }
            $user->save();
        });
    }

    public function deactivateEquipment(UserEquipment $userEquipment): void
    {
        DB::transaction(function () use ($userEquipment) {
            $user = $this->user;
            $type = $userEquipment->equipment->type;

            $userEquipment->active = false;
            $userEquipment->save();

            if ($type === 'armor') {
                $user->armor_id = null;
            } else {
                $user->weapon_id = null;
            }
            $user->save();
        });
    }

    // ==================== VITAL REGEN ======================
    public function restoreVital(VitalType $vital, int $amount): void
    {

        DB::transaction(function () use ($vital, $amount) {
            $cost = $amount * 10;
            $this->user->validateFunds($cost);

            switch ($vital) {
                case VitalType::Health:
                    $this->user->health = min($this->user->max_health, $this->user->health + $amount);
                    break;
                case VitalType::Stamina:
                    $this->user->stamina = min(100, $this->user->stamina + $amount);
                    break;

                case VitalType::Addiction:
                    $this->user->addiction = max(0, $this->user->addiction - $amount);
                    break;

                default:
                    throw new \InvalidArgumentException("Invalid vital type: {$vital->value}");
            }
            $this->user->adjustCash(-$cost);

            $this->user->save();
        });
    }

    public function rewardItem(Buyable $item, int $quantity): void
    {
        $item->addToUser($this->user, $quantity);
    }

    // ==================== FACTORY ======================
    public function upgradeFactory(UserFactory $userFactory): int
    {
        if ($userFactory->level >= 3) {
            throw new \RuntimeException("O nível máximo permitido para upgrade é 3.");
        }
        $cost = $userFactory->getUpgradeCost();
        DB::transaction(function () use ($userFactory, $cost) {
            $this->user->validateFunds($cost);
            $this->user->adjustCash(-$cost);

            $userFactory->levelUp($cost);
        });

        return $cost;
    }

    public function collectFactoryProduction()
    {
        $userId = $this->user->id;

        $production = UserFactory::where('user_id', $userId)
            ->where('stash', '>', 0)
            ->exists();

        if (!$production) {
            throw new \RuntimeException("Nothing to collect.");
        }

        DB::transaction(function () use ($userId) {
            DB::statement("
            INSERT INTO user_drugs (user_id, drug_id, amount)
            SELECT
                uf.user_id,
                f.drug_id,
                uf.stash
            FROM user_factories uf
            JOIN factories f ON f.id = uf.factory_id
            WHERE uf.user_id = ? AND uf.stash > 0
            ON DUPLICATE KEY UPDATE
                amount = amount + VALUES(amount)
        ", [$userId]);

            DB::statement("
            UPDATE user_factories
            SET stash = 0
            WHERE user_id = ? AND stash > 0
        ", [$userId]);
        });
    }

    private function calculateProductionDuration(int $basePerUnit, int $amount, int $level, float $minFactor = 0.2): int
    {
        // Efeito suavizador usando a raiz quadrada (sqrt)
        // Duração base de 2 minutos + raiz quadrada da quantidade de componentes (dividido por 1000 para escala)
        $scaledAmount = (int) max(1, $amount / 1000);
        $total = 2 + (int) floor(sqrt($scaledAmount));

        // Redução progressiva de acordo com o nível
        $factor = max($minFactor, 1.0 / $level);

        return (int) max(1, (int) round($total * $factor));
    }

    public function createLabProduction(UserFactory $userFactory, int $componentId, int $amount): void
    {
        DB::transaction(function () use ($userFactory, $componentId, $amount) {
            // Filas simultâneas de produção limitadas ao nível do laboratório
            $activeCount = $userFactory->productions()->count();
            if ($activeCount >= $userFactory->level) {
                throw new \RuntimeException("O laboratório nível {$userFactory->level} suporta no máximo {$userFactory->level} produção(ões) simultânea(s).");
            }

            $component = Component::findOrFail($componentId);
            $componentsPerUnit = $component->drug->getComponentsPerUnit();

            // Quantidade de componentes necessária para produzir a quantidade solicitada de droga
            $requiredComponents = $amount * $componentsPerUnit;

            // Capacidade total de processamento de componentes limitada à capacidade por nível (escala de 1000)
            $maxCapacity = $userFactory->factory->production * $userFactory->level * 1000;
            if ($requiredComponents > $maxCapacity) {
                throw new \RuntimeException("O laboratório nível {$userFactory->level} pode processar no máximo " . number_format($maxCapacity) . " componentes por fila. A produção de " . number_format($amount) . " unidades exige " . number_format($requiredComponents) . " componentes.");
            }

            $component->validateInventory($this->user, $requiredComponents);

            $component->removeFromUser($this->user, $requiredComponents);

            $duration = $this->calculateProductionDuration(1, $requiredComponents, $userFactory->level);

            LabProduction::create([
                'drug_id'         => $component->drug_id,
                'user_factory_id' => $userFactory->id,
                'amount'          => $amount,
                'ends_at'         => now()->addMinutes($duration),
            ]);
        });
    }

    public function cancelLabProduction(LabProduction $production)
    {
        $production->delete();
    }

    public function claimLabProduction(LabProduction $production)
    {
        DB::transaction(function () use ($production) {
            $production->drug->addToUser($this->user, $production->amount);
            $production->delete();
        });
    }

    // ==================== HOOKER ======================
    public function collectHookerIncome(): array
    {
        $income = $this->user->hooker_income;

        if (!$income || $income == 0) {
            throw new \RuntimeException("Nothing to collect.");
        }

        $escapedHooker = null;
        $escapedCount = 0;

        DB::transaction(function () use ($income, &$escapedHooker, &$escapedCount) {
            $this->user->adjustCash($income);
            $this->user->increment('hooker_profits', $income);
            DB::table('user_hookers')->where('user_id', $this->user->id)->update(['available_income' => 0]);

            // Random escape event: 25% chance
            if (rand(1, 100) <= 25) {
                // Get one random hooker type that the user currently owns
                $owned = $this->user->hookers()->wherePivot('amount', '>', 0)->inRandomOrder()->first();
                if ($owned) {
                    $amountOwned = $owned->pivot->amount;
                    $escapedCount = (int) max(1, (int) ($amountOwned * 0.05));
                    $owned->removeFromUser($this->user, $escapedCount);
                    $escapedHooker = $owned;
                }
            }
        });

        return [
            'income' => $income,
            'escaped' => $escapedHooker !== null,
            'hooker_name' => $escapedHooker ? $escapedHooker->name : null,
            'escaped_count' => $escapedCount,
        ];
    }

    // ==================== JAIL ======================
    public function sendToJail(int $minutes = 30): void
    {
        $this->user->jail_end_time = Carbon::now()->addMinutes($minutes);
        $this->user->save();
    }

    public function releaseFromJail(): void
    {
        $this->user->jail_end_time = null;
        $this->user->save();
    }

    public function bribeJailGuard(): void
    {
        $cost = $this->user->jail_release_cost;

        $this->user->validateFunds($cost);

        DB::transaction(function () use ($cost) {
            $this->user->adjustCash(-$cost);
            $this->releaseFromJail();
        });
    }

    // ==================== HOSPITAL ======================
    public function sendToHospital(int $minutes = 30): void
    {
        $this->user->hospital_end_time = Carbon::now()->addMinutes($minutes);
        $this->user->save();
    }

    public function releaseFromHospital(): void
    {
        $this->user->hospital_end_time = null;
        $this->user->save();
    }

    public function calculateStaminaBoostCost(): int
    {
        return max(100, (int) ($this->user->respect * 5));
    }

    public function calculateDetoxCost(): int
    {
        return max(200, (int) ($this->user->respect * 10));
    }

    public function buyStaminaBoost(): void
    {
        $cost = $this->calculateStaminaBoostCost();

        if ($this->user->stamina >= 100) {
            throw new \RuntimeException("Sua stamina já está cheia!");
        }

        $this->user->validateFunds($cost);

        DB::transaction(function () use ($cost) {
            $this->user->adjustCash(-$cost);
            $this->user->stamina = 100;
            $this->user->addiction = min(100, $this->user->addiction + 15);
            $this->user->save();
        });
    }

    public function buyDetoxification(): void
    {
        $cost = $this->calculateDetoxCost();

        if ($this->user->addiction <= 0) {
            throw new \RuntimeException("Você não possui nenhum vício para tratar!");
        }

        $this->user->validateFunds($cost);

        DB::transaction(function () use ($cost) {
            $this->user->adjustCash(-$cost);
            $this->user->addiction = 0;
            $this->user->save();
        });
    }

    // ==================== BOATE ======================
    public function fight(User $victim): array
    {
        $attacker = $this->user;

        if ($attacker->health < 10) {
            throw new \RuntimeException("Muito fraco para lutar. Vá ao hospital!");
        }

        // Check stamina
        $staminaCost = 20;
        if ($attacker->stamina < $staminaCost) {
            throw new \RuntimeException("Sem stamina suficiente para atacar!");
        }

        return DB::transaction(function () use ($attacker, $victim, $staminaCost) {
            // Reduce attacker stamina
            $attacker->stamina -= $staminaCost;

            // Compare assault powers
            $attackerPower = $attacker->assault_power;
            $victimPower   = $victim->assault_power;

            // Randomize slightly to avoid deterministic outcome
            $attackerRoll = $attackerPower + rand(0, 10);
            $victimRoll   = $victimPower + rand(0, 10);

            $winner = $attackerRoll >= $victimRoll ? $attacker : $victim;
            $loser  = $winner->is($attacker) ? $victim : $attacker;

            // Apply health loss
            $winner->health = max(1, $winner->health - rand(5, 15)); // Winner loses some health
            $loser->health  = 0; // Loser is killed
            $loser->hospital_end_time = Carbon::now()->addMinutes(15);

            $rewardCash = 0;
            // If attacker wins, reward them
            if ($winner->is($attacker)) {
                $rewardCash = (int) ($victim->cash * 0.1); // Take 10% of victim's cash
                $attacker->adjustCash($rewardCash);
                $victim->adjustCash(-$rewardCash);

                // Reward stats
                $statReward = 2; // Example: 2 points each
                $attacker->strength     += $statReward;
                $attacker->intelligence += $statReward;
                $attacker->charisma     += $statReward;
                $attacker->tolerance    += $statReward;

                // Register kill
                DB::table('user_kills')->insert([
                    'killer_id' => $attacker->id,
                    'victim_id' => $victim->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Persist changes
            $attacker->save();
            $victim->save();

            return [
                'winner' => $winner->id,
                'loser' => $loser->id,
                'rewardCash' => $rewardCash,
            ];
        });
    }

    // ==================== ROBBERY ======================
    public function calculateSuccessChance(\App\Models\Robbery $robbery): int
    {
        if ($robbery->required_power <= 0) {
            return 100;
        }
        $chance = (int) floor(($this->user->single_robbery_power / $robbery->required_power) * 100);
        return $chance > 100 ? 100 : $chance;
    }

    public function executeRobbery(\App\Models\Robbery $robbery): array
    {
        if ($this->user->in_jail || $this->user->in_hospital) {
            throw new \RuntimeException("Você não pode realizar roubos no momento!");
        }

        if ($this->user->stamina < $robbery->required_stamina) {
            throw new \RuntimeException("Você não possui stamina suficiente para este roubo!");
        }

        return DB::transaction(function () use ($robbery) {
            // Deduct stamina
            $this->user->stamina = max(0, $this->user->stamina - $robbery->required_stamina);
            $this->user->save();

            // Calculate chance
            $chance = $this->calculateSuccessChance($robbery);
            $isSuccess = $chance >= 100 || (rand(1, 100) <= $chance);

            if ($isSuccess) {
                // Success: Adjust cash
                if ($robbery->cash > 0) {
                    $this->user->adjustCash($robbery->cash);
                }

                // Adjust stats & respect
                $statReward = (int) max(1, floor(sqrt($robbery->required_power) / 2));
                $this->user->adjustStats($statReward);
                $this->user->save();

                // Add drugs
                if (!empty($robbery->drugs)) {
                    foreach ($robbery->drugs as $d) {
                        $drugModel = \App\Models\Drug::findOrFail($d['drug_id']);
                        $drugModel->addToUser($this->user, $d['amount']);
                    }
                }

                // Add components
                if (!empty($robbery->components)) {
                    foreach ($robbery->components as $c) {
                        $compModel = \App\Models\Component::findOrFail($c['component_id']);
                        $compModel->addToUser($this->user, $c['amount']);
                    }
                }

                // Record success
                $pivot = DB::table('user_robberies')
                    ->where('user_id', $this->user->id)
                    ->where('robbery_id', $robbery->id)
                    ->first();
                if ($pivot) {
                    DB::table('user_robberies')
                        ->where('id', $pivot->id)
                        ->increment('success_count');
                } else {
                    DB::table('user_robberies')->insert([
                        'user_id' => $this->user->id,
                        'robbery_id' => $robbery->id,
                        'success_count' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $successMessages = [
                    "Sucesso! Você levou a melhor e a polícia comeu poeira. Pegue seu espólio!",
                    "Operação concluída. O alvo nem viu de onde veio o golpe. Dinheiro fácil!",
                    "Sensacional! Tudo correu como planejado e o lucro está garantido.",
                    "Perfeito! Você limpou o local e saiu assobiando."
                ];
                $message = $successMessages[array_rand($successMessages)];

                return [
                    'success' => true,
                    'message' => $message,
                    'cash' => $robbery->cash,
                    'drugs' => $robbery->drugs,
                    'components' => $robbery->components,
                ];
            } else {
                // Failure: Record fail
                $pivot = DB::table('user_robberies')
                    ->where('user_id', $this->user->id)
                    ->where('robbery_id', $robbery->id)
                    ->first();
                if ($pivot) {
                    DB::table('user_robberies')
                        ->where('id', $pivot->id)
                        ->increment('fail_count');
                } else {
                    DB::table('user_robberies')->insert([
                        'user_id' => $this->user->id,
                        'robbery_id' => $robbery->id,
                        'fail_count' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Send to jail for 2 minutes
                $this->sendToJail(2);

                $failMessages = [
                    "Deu ruim! A polícia apareceu do nada e você levou um belo sermão antes de ver o sol nascer quadrado por 2 minutos.",
                    "Fracasso! O alarme disparou, você tropeçou no próprio sapato e agora vai passar 2 minutos na cela limpando privada.",
                    "Preso! O plano era perfeito, mas a execução... bem, você está em cana por 2 minutos!",
                    "Que vergonha! Você foi pego por um segurança aposentado de 70 anos e agora está trancafiado por 2 minutos."
                ];
                $message = $failMessages[array_rand($failMessages)];

                return [
                    'success' => false,
                    'message' => $message,
                    'cash' => 0,
                    'drugs' => [],
                    'components' => [],
                ];
            }
        });
    }
}
