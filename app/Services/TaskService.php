<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskParam;
use App\Models\TaskCategory;
use App\Models\Drug;
use App\Models\Equipment;
use App\Models\Hooker;
use App\Models\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function evaluateRequirement(User $user, TaskParam $tp): array
    {
        $param = $tp->game_param;
        $target = $param?->target;
        $total = $tp->value;
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
            case 'equipment_active':
                if ($target instanceof Equipment) {
                    $isActive = DB::table('user_equipment')
                        ->where('user_id', $user->id)
                        ->where('equipment_id', $target->id)
                        ->where('active', true)
                        ->exists() || ($user->armor_id === $target->id || $user->weapon_id === $target->id);
                    $current = $isActive ? 1 : 0;
                }
                break;
            case 'hooker_type_owned':
                if ($target instanceof Hooker) {
                    $userHooker = DB::table('user_hookers')->where('user_id', $user->id)->where('hooker_id', $target->id)->first();
                    $current = $userHooker ? $userHooker->amount : 0;
                }
                break;
            case 'single_robbery_count':
                $current = DB::table('user_robberies')
                    ->join('robberies', 'user_robberies.robbery_id', '=', 'robberies.id')
                    ->where('user_robberies.user_id', $user->id)
                    ->where('robberies.type', 'solo')
                    ->sum('user_robberies.success_count');
                break;
            case 'kills_count':
                $current = DB::table('user_kills')->where('killer_id', $user->id)->count();
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
            'id' => $tp->id,
            'name' => $displayName,
            'param_name' => $param->name,
            'current' => $current,
            'total' => $total,
            'progress' => $progress,
            'completed' => $completed,
        ];
    }

    public function grantRewards(User $user, Task $task): void
    {
        $rewards = $task->getRewards();

        foreach ($rewards as $tp) {
            $param = $tp->game_param;
            $target = $param?->target;
            $amount = $tp->value;

            switch ($param->name) {
                case 'cash':
                    $user->adjustCash($amount);
                    break;
                case 'respect':
                    // Respect is calculated dynamically from cash ($1 = 1/30000 respect)
                    $user->adjustCash($amount * 30000);
                    break;
                case 'available_stats':
                    $user->increment('available_stats', $amount);
                    break;
                case 'drug_received':
                    if ($target instanceof Drug) {
                        $target->addToUser($user, $amount);
                    }
                    break;
                case 'component_received':
                    if ($target instanceof Component) {
                        $target->addToUser($user, $amount);
                    }
                    break;
                case 'hooker_received':
                    if ($target instanceof Hooker) {
                        $target->addToUser($user, $amount);
                    }
                    break;
            }
        }
    }

    public function canCompleteTask(User $user, Task $task): bool
    {
        // 1. Check if already completed
        $alreadyCompleted = DB::table('user_completed_tasks')
            ->where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->exists();
        if ($alreadyCompleted) {
            return false;
        }

        // 2. Check Order progression constraint:
        // Any task in the same category with a lower order must be completed.
        $hasUncompletedPriorTasks = Task::where('task_category_id', $task->task_category_id)
            ->where('order', '<', $task->order)
            ->whereNotExists(function ($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('user_completed_tasks')
                    ->whereColumn('user_completed_tasks.task_id', 'tasks.id')
                    ->where('user_completed_tasks.user_id', $user->id);
            })
            ->exists();
        if ($hasUncompletedPriorTasks) {
            return false;
        }

        // 3. Evaluate requirements
        $requirements = $task->getRequirements();
        foreach ($requirements as $tp) {
            $eval = $this->evaluateRequirement($user, $tp);
            if (!$eval['completed']) {
                return false;
            }
        }

        return true;
    }

    public function completeTask(User $user, Task $task): void
    {
        if (!$this->canCompleteTask($user, $task)) {
            throw new \RuntimeException("Você ainda não cumpre todos os requisitos para esta tarefa ou precisa completar as anteriores primeiro!");
        }

        DB::transaction(function () use ($user, $task) {
            // Register completion
            DB::table('user_completed_tasks')->insert([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'completed_at' => now(),
            ]);

            // Grant rewards
            $this->grantRewards($user, $task);
        });
    }

    public function allocateStats(User $user, string $stat, int $amount): void
    {
        if ($amount <= 0) {
            throw new \RuntimeException("A quantidade deve ser maior que zero.");
        }

        if ($user->available_stats < $amount) {
            throw new \RuntimeException("Pontos de atributos insuficientes.");
        }

        $validStats = ['strength', 'intelligence', 'charisma', 'tolerance'];
        if (!in_array($stat, $validStats)) {
            throw new \RuntimeException("Atributo inválido.");
        }

        DB::transaction(function () use ($user, $stat, $amount) {
            $user->decrement('available_stats', $amount);
            $user->increment($stat, $amount);
        });
    }
}
