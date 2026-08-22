<?php

namespace App\Services;

use App\Models\Career;
use App\Models\CareerLevel;
use App\Models\CareerLevelParam;
use App\Models\Drug;
use App\Models\Equipment;
use App\Models\Hooker;
use App\Models\User;
use App\Models\UserEquipment;
use Illuminate\Support\Facades\DB;

class CareerService
{
    public function getRequirements(Career $career, int $level)
    {
        $careerLevel = CareerLevel::where('career_id', $career->id)->where('level', $level)->first();
        if (!$careerLevel) return collect();

        return $careerLevel->getRequirements();
    }

    public function getRewards(Career $career, int $level)
    {
        $careerLevel = CareerLevel::where('career_id', $career->id)->where('level', $level)->first();
        if (!$careerLevel) return collect();

        return $careerLevel->getRewards();
    }

    public function evaluateRequirement(User $user, CareerLevelParam $clp): array
    {
        $param = $clp->game_param;
        $target = $param?->target;
        $total = $clp->value;
        $current = 0;

        switch ($param->name) {
            case 'cash':
                $current = $user->cash;
                break;
            case 'respect':
                $current = $user->respect;
                break;
            case 'hookers_count':
                $current = DB::table('user_hookers')->where('user_id', $user->id)->sum('amount');
                break;
            case 'stats_total':
                $current = $user->strength + $user->tolerance + $user->charisma + $user->intelligence;
                break;
            case 'drug_sold':
                if ($target instanceof Drug) {
                    $userDrug = DB::table('user_drugs')->where('user_id', $user->id)->where('drug_id', $target->id)->first();
                    $current = $userDrug ? $userDrug->total_sold : 0;
                } else {
                    $current = DB::table('user_drugs')->where('user_id', $user->id)->sum('total_sold');
                }
                break;
            case 'equipment_owned':
                if ($target instanceof Equipment) {
                    $hasEquipment = DB::table('user_equipment')->where('user_id', $user->id)->where('equipment_id', $target->id)->exists();
                    $current = $hasEquipment ? 1 : 0;
                }
                break;
            case 'hooker_type_owned':
                if ($target instanceof Hooker) {
                    $userHooker = DB::table('user_hookers')->where('user_id', $user->id)->where('hooker_id', $target->id)->first();
                    $current = $userHooker ? $userHooker->amount : 0;
                }
                break;
            default:
                $current = 0;
                break;
        }

        $completed = $current >= $total;
        $progress = min(100, (int) round(($current / max(1, $total)) * 100));

        $displayName = \App\Enums\GameParamType::getLabel($param->name);
        if ($target) {
            $displayName .= " (" . ($target->name ?? 'Item') . ")";
        }

        return [
            'id' => $clp->id,
            'name' => $displayName,
            'param_name' => $param->name,
            'current' => $current,
            'total' => $total,
            'progress' => $progress,
            'completed' => $completed,
        ];
    }

    public function getUserCurrentLevelNumber(User $user): int
    {
        if (!$user->career_level_id) return 1;
        return $user->careerLevel?->level ?? 1;
    }

    public function canLevelUp(User $user): bool
    {
        if (!$user->career_id) return false;

        $currentLevelNumber = $this->getUserCurrentLevelNumber($user);
        $nextLevelNumber = $currentLevelNumber + 1;
        $nextLevel = CareerLevel::where('career_id', $user->career_id)->where('level', $nextLevelNumber)->first();

        if (!$nextLevel) return false;

        $requirements = $nextLevel->getRequirements();
        if ($requirements->isEmpty()) return true;

        foreach ($requirements as $clp) {
            $eval = $this->evaluateRequirement($user, $clp);
            if (!$eval['completed']) {
                return false;
            }
        }

        return true;
    }

    public function grantRewards(User $user, CareerLevel $level): void
    {
        $rewards = $level->getRewards();

        foreach ($rewards as $clp) {
            $param = $clp->game_param;
            $target = $param?->target;
            $amount = $clp->value;

            switch ($param->name) {
                case 'cash':
                    $user->adjustCash($amount);
                    break;
                case 'respect':
                    // Respect is calculated dynamically from cash ($1 = 1/30000 respect)
                    $user->adjustCash($amount * 30000);
                    break;
                case 'stamina':
                    $user->setVitals(\App\Enums\VitalType::STAMINA, min(100, $user->stamina + $amount));
                    break;
                case 'drug_received':
                    if ($target instanceof Drug) {
                        $userDrug = DB::table('user_drugs')->where('user_id', $user->id)->where('drug_id', $target->id)->first();
                        if ($userDrug) {
                            DB::table('user_drugs')->where('id', $userDrug->id)->update([
                                'amount' => $userDrug->amount + $amount,
                                'updated_at' => now(),
                            ]);
                        } else {
                            DB::table('user_drugs')->insert([
                                'user_id' => $user->id,
                                'drug_id' => $target->id,
                                'amount' => $amount,
                                'total_sold' => 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                    break;
                case 'equipment_received':
                    if ($target instanceof Equipment) {
                        UserEquipment::firstOrCreate([
                            'user_id' => $user->id,
                            'equipment_id' => $target->id,
                        ]);
                    }
                    break;
                case 'hooker_received':
                    if ($target instanceof Hooker) {
                        $userHooker = DB::table('user_hookers')->where('user_id', $user->id)->where('hooker_id', $target->id)->first();
                        if ($userHooker) {
                            DB::table('user_hookers')->where('id', $userHooker->id)->update([
                                'amount' => $userHooker->amount + $amount,
                                'updated_at' => now(),
                            ]);
                        } else {
                            DB::table('user_hookers')->insert([
                                'user_id' => $user->id,
                                'hooker_id' => $target->id,
                                'amount' => $amount,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                    break;
            }
        }
    }

    public function levelUp(User $user): bool
    {
        if (!$user->career_id) {
            throw new \RuntimeException("Nenhuma carreira selecionada.");
        }

        $currentLevelNumber = $this->getUserCurrentLevelNumber($user);
        $nextLevelNumber = $currentLevelNumber + 1;

        $nextLevel = CareerLevel::where('career_id', $user->career_id)->where('level', $nextLevelNumber)->first();
        if (!$nextLevel) {
            throw new \RuntimeException("Você já atingiu o nível máximo desta carreira!");
        }

        if (!$this->canLevelUp($user)) {
            throw new \RuntimeException("Você ainda não cumpriu todos os requisitos para este nível!");
        }

        DB::transaction(function () use ($user, $nextLevel) {
            $user->career_level_id = $nextLevel->id;
            $user->save();

            $this->grantRewards($user, $nextLevel);
        });

        $user->unsetRelation('careerLevel');
        $user->refresh();

        return true;
    }
}
