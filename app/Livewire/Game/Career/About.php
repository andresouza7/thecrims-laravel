<?php

namespace App\Livewire\Game\Career;

use App\Models\Career as CareerModel;
use App\Services\GameFacade;
use Livewire\Component;

class About extends Component
{
    public $selectedCareerId;

    public function selectCareer(GameFacade $game)
    {
        $this->validate(['selectedCareerId' => 'required|exists:careers,id']);

        try {
            $game->user->update(['career_id' => $this->selectedCareerId]);
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Carreira escolhida com sucesso!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        $careers = CareerModel::orderBy('name')->get();
        $userCareer = $game->user->career()->with(['levels' => function ($query) {
            $query->orderBy('level');
        }])->first();

        if ($userCareer) {
            $userCareer->levels->transform(function ($level) {
                return [
                    'id' => $level->id,
                    'level' => $level->level,
                    'name' => $level->name,
                    'requirements' => $level->getRequirements(),
                    'rewards' => $level->getRewards(),
                ];
            });
        }

        return view('livewire.game.career.about', [
            'userCareer' => $userCareer,
            'careers' => $careers,
        ]);
    }
}
