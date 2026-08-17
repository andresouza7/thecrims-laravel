<?php

namespace App\Livewire\Game\Career;

use App\Models\Career as CareerModel;
use App\Models\CareerLevel;
use App\Services\CareerService;
use App\Services\GameFacade;
use Livewire\Component;

class About extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public $selectedCareerId;

    public function mount(GameFacade $game)
    {
        if (!$game->user->career_id) {
            $firstCareer = CareerModel::first();
            if ($firstCareer) {
                $this->selectedCareerId = $firstCareer->id;
            }
        }
    }

    public function selectCareer(GameFacade $game)
    {
        $this->validate(['selectedCareerId' => 'required|exists:careers,id']);

        try {
            $level1 = CareerLevel::where('career_id', $this->selectedCareerId)->where('level', 1)->first();

            $game->user->update([
                'career_id' => $this->selectedCareerId,
                'career_level_id' => $level1?->id,
            ]);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Carreira escolhida com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function promoteLevel(GameFacade $game, CareerService $careerService)
    {
        try {
            $careerService->levelUp($game->user);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Parabéns! Você subiu de nível na sua carreira e recebeu suas recompensas!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render(GameFacade $game, CareerService $careerService)
    {
        $user = $game->user;
        $user->unsetRelation('careerLevel');
        $user->refresh();

        $careers = CareerModel::orderBy('name')->get();
        $userCareer = $user->career;

        $levelsData = [];
        $canPromote = false;
        $hasNextLevel = false;
        $currentLevelNum = $careerService->getUserCurrentLevelNumber($user);

        if ($userCareer) {
            $hasNextLevel = CareerLevel::where('career_id', $userCareer->id)->where('level', $currentLevelNum + 1)->exists();
            $minDisplayLevel = max(2, $currentLevelNum);
            $levels = CareerLevel::where('career_id', $userCareer->id)
                ->where('level', '>=', $minDisplayLevel)
                ->orderBy('level')
                ->get();

            foreach ($levels as $lvl) {
                $rawReqs = $lvl->getRequirements();
                $rawRews = $lvl->getRewards();

                $evaluatedReqs = $rawReqs->map(function ($clp) use ($user, $careerService) {
                    return $careerService->evaluateRequirement($user, $clp);
                });

                $formattedRewards = $rawRews->map(function ($clp) {
                    $param = $clp->game_param;
                    $target = $param?->target;
                    $name = $param->name;
                    if ($target) {
                        $name .= " (" . ($target->name ?? 'Item') . ")";
                    }
                    return [
                        'id' => $clp->id,
                        'name' => $name,
                        'value' => $clp->value,
                    ];
                });

                $levelsData[] = [
                    'id' => $lvl->id,
                    'level' => $lvl->level,
                    'name' => $lvl->name,
                    'is_current' => $currentLevelNum == $lvl->level,
                    'is_unlocked' => $currentLevelNum >= $lvl->level,
                    'requirements' => $evaluatedReqs,
                    'rewards' => $formattedRewards,
                ];
            }

            $canPromote = $careerService->canLevelUp($user);
        }

        // Preview calculation when choosing career or inspecting careers
        $previewCareer = null;
        $previewLevelsData = [];

        $inspectCareerId = $this->selectedCareerId;

        if ($inspectCareerId) {
            $previewCareer = CareerModel::find($inspectCareerId);
            if ($previewCareer) {
                $previewLevels = CareerLevel::where('career_id', $previewCareer->id)
                    ->where('level', '>=', 2)
                    ->orderBy('level')
                    ->get();

                foreach ($previewLevels as $lvl) {
                    $rawReqs = $lvl->getRequirements();
                    $rawRews = $lvl->getRewards();

                    $reqs = $rawReqs->map(function ($clp) {
                        $param = $clp->game_param;
                        $target = $param?->target;
                        $name = $param->name;
                        if ($target) {
                            $name .= " (" . ($target->name ?? 'Item') . ")";
                        }
                        return [
                            'id' => $clp->id,
                            'name' => $name,
                            'value' => $clp->value,
                        ];
                    });

                    $rews = $rawRews->map(function ($clp) {
                        $param = $clp->game_param;
                        $target = $param?->target;
                        $name = $param->name;
                        if ($target) {
                            $name .= " (" . ($target->name ?? 'Item') . ")";
                        }
                        return [
                            'id' => $clp->id,
                            'name' => $name,
                            'value' => $clp->value,
                        ];
                    });

                    $previewLevelsData[] = [
                        'id' => $lvl->id,
                        'level' => $lvl->level,
                        'name' => $lvl->name,
                        'requirements' => $reqs,
                        'rewards' => $rews,
                    ];
                }
            }
        }

        return view('livewire.game.career.about', [
            'user' => $user,
            'userCareer' => $userCareer,
            'currentLevelNum' => $currentLevelNum,
            'careers' => $careers,
            'levelsData' => $levelsData,
            'canPromote' => $canPromote,
            'hasNextLevel' => $hasNextLevel,
            'previewCareer' => $previewCareer,
            'previewLevelsData' => $previewLevelsData,
        ]);
    }
}
