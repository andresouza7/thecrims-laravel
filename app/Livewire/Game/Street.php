<?php

namespace App\Livewire\Game;

use App\Models\TaskCategory;
use App\Models\Task;
use App\Services\GameFacade;
use App\Services\TaskService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Street extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public function startCategory(int $categoryId, GameFacade $game)
    {
        try {
            $category = TaskCategory::findOrFail($categoryId);
            $game->user->update([
                'active_task_category_id' => $category->id
            ]);

            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: "Categoria \"{$category->name}\" iniciada com sucesso!");
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function pauseCategory(GameFacade $game)
    {
        try {
            $game->user->update([
                'active_task_category_id' => null
            ]);

            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'info', message: "Progresso pausado. Você pode retornar a esta categoria mais tarde!");
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function claimTaskReward(int $taskId, GameFacade $game, TaskService $taskService)
    {
        try {
            $task = Task::findOrFail($taskId);
            $taskService->completeTask($game->user, $task);

            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: "Tarefa \"{$task->name}\" concluída! Recompensas recebidas!");
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render(GameFacade $game, TaskService $taskService)
    {
        $user = $game->user;
        $user->unsetRelation('activeTaskCategory');
        $user->refresh();

        $activeCategory = $user->activeTaskCategory;
        $categoriesData = [];
        $tasksData = [];

        if ($activeCategory) {
            // Category is active, fetch its tasks ordered
            $tasks = $activeCategory->tasks;

            // Track completion to check for strict order locking
            $firstUncompletedFound = false;

            foreach ($tasks as $task) {
                $isCompleted = DB::table('user_completed_tasks')
                    ->where('user_id', $user->id)
                    ->where('task_id', $task->id)
                    ->exists();

                $requirements = $task->getRequirements()->map(function ($tp) use ($user, $taskService) {
                    return $taskService->evaluateRequirement($user, $tp);
                });

                $rewards = $task->getRewards()->map(function ($tp) {
                    $param = $tp->game_param;
                    $target = $param?->target;
                    $name = $param->name;

                    // Friendlier display name for rewards
                    $friendlyRewardNames = [
                        'cash' => 'Dinheiro',
                        'respect' => 'Respeito',
                        'available_stats' => 'Pontos de Atributo',
                        'drug_received' => 'Droga',
                        'component_received' => 'Componente',
                        'hooker_received' => 'Garota (Prostituta)',
                    ];

                    $displayName = $friendlyRewardNames[$name] ?? $name;

                    if ($target) {
                        $displayName .= " (" . ($target->name ?? 'Item') . ")";
                    }

                    return [
                        'name' => $displayName,
                        'value' => $tp->value,
                    ];
                });

                // Strict progression check: if there is a prior task that is not completed, this one is locked.
                $isLocked = false;
                if (!$isCompleted) {
                    if ($firstUncompletedFound) {
                        $isLocked = true;
                    }
                    $firstUncompletedFound = true;
                }

                $canComplete = !$isCompleted && !$isLocked && $taskService->canCompleteTask($user, $task);

                $tasksData[] = [
                    'id' => $task->id,
                    'name' => $task->name,
                    'description' => $task->description,
                    'order' => $task->order,
                    'completed' => $isCompleted,
                    'locked' => $isLocked,
                    'can_complete' => $canComplete,
                    'requirements' => $requirements,
                    'rewards' => $rewards,
                ];
            }
        } else {
            // No category is active, list all categories with progress bars
            $categories = TaskCategory::all();

            foreach ($categories as $cat) {
                $totalTasks = $cat->tasks()->count();
                $completedTasksCount = DB::table('user_completed_tasks')
                    ->join('tasks', 'user_completed_tasks.task_id', '=', 'tasks.id')
                    ->where('tasks.task_category_id', $cat->id)
                    ->where('user_completed_tasks.user_id', $user->id)
                    ->count();

                $progressPercent = $totalTasks > 0 ? (int) round(($completedTasksCount / $totalTasks) * 100) : 0;

                $categoriesData[] = [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'description' => $cat->description,
                    'total_tasks' => $totalTasks,
                    'completed_tasks' => $completedTasksCount,
                    'progress_percent' => $progressPercent,
                ];
            }
        }

        return view('livewire.game.street', [
            'user' => $user,
            'activeCategory' => $activeCategory,
            'categoriesData' => $categoriesData,
            'tasksData' => $tasksData,
        ])->layout('layouts.app');
    }
}
