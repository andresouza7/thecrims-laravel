<?php

namespace App\Livewire\Game;

use App\Models\Drug as DrugModel;
use App\Services\GameFacade;
use Livewire\Component;

class Drug extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public array $amounts = [];

    public function sell($drugId, GameFacade $game)
    {
        $amount = $this->amounts[$drugId] ?? 0;

        if ($amount <= 0) {
            session()->flash('error', 'Informe uma quantidade válida!');
            return;
        }

        try {
            $drug = DrugModel::findOrFail($drugId);
            $game->action()->sell($drug, $amount);
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Drogas vendidas com sucesso!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function reward(GameFacade $game)
    {
        try {
            $drug = DrugModel::inRandomOrder()->first();
            if ($drug) {
                $game->action()->rewardItem($drug, 200);
                $this->dispatch('user-stats-updated');
                session()->flash('message', 'Drogas de recompensa adicionadas!');
            }
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        return view('livewire.game.drug', [
            'drugs' => $game->user->drugs,
        ]);
    }
}
