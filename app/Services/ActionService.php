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

    public function deposit(int $amount): void
    {
        if ($amount <= 0 || $this->user->cash < $amount) {
            throw new \RuntimeException('Not enough funds to make deposit');
        }

        DB::transaction(function () use ($amount) {
            $this->user->adjustCash(-$amount);
            $this->user->increment('bank', $amount);
        });
    }

    public function withdraw(int $amount): void
    {
        if ($amount <= 0 || $this->user->bank < $amount) {
            throw new \RuntimeException('Not enough funds to withdraw');
        }

        DB::transaction(function () use ($amount) {
            $this->user->adjustCash($amount);
            $this->user->decrement('bank', $amount);
        });
    }

    public function buy(Buyable $item, int $quantity = 1): void
    {
        DB::transaction(function () use ($item, $quantity) {
            $cost = $item->getPrice() * $quantity;

            $this->user->validateFunds($cost);
            $this->user->adjustCash(-$cost);

            $item->addToUser($this->user, $quantity);
        });
    }

    public function sell(Sellable $item, int $quantity = 1): void
    {
        DB::transaction(function () use ($item, $quantity) {
            $item->validateInventory($this->user, $quantity);

            $profit = $item->getPrice() * $quantity;

            $this->user->adjustCash($profit);
            $item->removeFromUser($this->user, $quantity);

            $this->user->increment('drug_profits', $profit);
        });
    }

    public function activateEquipment(UserEquipment $userEquipment): void
    {
        if ($userEquipment->equipment->type === 'armor') {
            $this->user->armor_id = $userEquipment->equipment_id;
        } else {
            $this->user->weapon_id = $userEquipment->equipment_id;
        }

        $this->user->save();
    }

    public function deactivateEquipment(UserEquipment $userEquipment): void
    {
        if ($userEquipment->equipment->type === 'armor') {
            $this->user->armor_id = null;
        } else {
            $this->user->weapon_id = null;
        }

        $this->user->save();
    }

    public function fight(User $victim)
    {
        $attacker = $this->user;

        $winner = null;
        $loser = null;
        $rewardCash = 0;

        DB::transaction(function () use ($attacker, $victim, &$winner, &$loser, &$rewardCash) {
            if ($attacker->health < 10) {
                throw new \Exception("Too weak to perform this action.");
            }

            // Check stamina
            $staminaCost = 20;
            if ($attacker->stamina < $staminaCost) {
                throw new \Exception("Not enough stamina to perform this action.");
            }

            // Reduce attacker stamina
            $attacker->adjustVitals(VitalType::STAMINA, -$staminaCost);

            // Randomize slightly to avoid deterministic outcome
            $attackerRoll = $attacker->assault_power + rand(0, 10);
            $victimRoll   = $victim->assault_power + rand(0, 10);

            $winner = $attackerRoll >= $victimRoll ? $attacker : $victim;
            $loser  = $winner->is($attacker) ? $victim : $attacker;

            // Apply health loss
            $winner->setVitals(VitalType::HEALTH, max(1, $winner->health - rand(5, 15)));
            $loser->setVitals(VitalType::HEALTH, 0);

            $rewardCash = 0;
            // If attacker wins, reward them
            if ($winner->is($attacker)) {
                $rewardCash = (int) ($victim->cash * 0.1); // Take victim's cash
                $attacker->adjustCash($rewardCash);
                $victim->adjustCash(-$rewardCash);

                // Reward stats
                $statReward = 2; // Example: 2 points each
                $attacker->adjustStats($statReward);

                // Register kill
                DB::table('user_kills')->insert([
                    'killer_id' => $attacker->id,
                    'victim_id' => $victim->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->sendToHospital();
            }
        });

        return [
            'winner' => $winner ? $winner->id : null,
            'loser' => $loser ? $loser->id : null,
            'rewardCash' => $rewardCash,
        ];
    }

    public function rewardItem(Buyable $item, int $quantity): void
    {
        $item->addToUser($this->user, $quantity);
    }

    // ==================== FACTORY ======================
    public function upgradeFactory(UserFactory $userFactory): void
    {
        DB::transaction(function () use ($userFactory) {
            $cost = 2000;

            $this->user->validateFunds($cost);
            $this->user->adjustCash(-$cost);

            $userFactory->levelUp($cost);
        });
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
        $total = $basePerUnit * $amount;

        // 5% faster per level, but not lower than $minFactor
        $factor = max($minFactor, 1 - ($level * 0.05));

        return (int) round($total * $factor);
    }

    public function createLabProduction(UserFactory $userFactory, int $componentId, int $amount): void
    {
        DB::transaction(function () use ($userFactory, $componentId, $amount) {
            $component = Component::findOrFail($componentId);
            $component->validateInventory($this->user, $amount);

            $component->removeFromUser($this->user, $amount);

            $duration = $this->calculateProductionDuration(1, $amount, $userFactory->level);

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
    public function collectHookerIncome()
    {
        $income = $this->user->hooker_income;

        if (!$income || $income == 0) {
            throw new \RuntimeException("Nothing to collect.");
        }

        DB::transaction(function () use ($income) {
            $this->user->adjustCash($income);
            $this->user->increment('hooker_profits', $income);
            DB::table('user_hookers')->where('user_id', $this->user->id)->update(['available_income' => 0]);
        });
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

    public function bribeJailGuard()
    {
        DB::transaction(function () {
            $cost = $this->user->jail_release_cost;

            $this->user->validateFunds($this->user->jail_release_cost);
            $this->user->adjustCash(-$cost);
            $this->releaseFromJail();
        });
    }

    // ==================== HOSPITAL ======================
    public function detox($cost = 100)
    {
        $this->user->validateFunds($cost);
        $this->user->setVitals(VitalType::STAMINA, 100);
    }

    public function sendToHospital(int $minutes = 15)
    {
        $this->user->hospital_end_time = Carbon::now()->addMinutes($minutes);
        $this->user->save();
    }

    public function releaseFromHospital()
    {
        DB::transaction(function () {
            $cost = $this->user->hospital_release_cost;

            $this->user->validateFunds($cost);
            $this->user->adjustCash(-$cost);
            $this->user->hospital_end_time = null;
            $this->user->save();
        });
    }
}
