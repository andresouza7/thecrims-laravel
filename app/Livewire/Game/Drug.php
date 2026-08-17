<?php

namespace App\Livewire\Game;

use App\Models\Drug as DrugModel;
use App\Services\GameFacade;
use Livewire\Component;

class Drug extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public $selectedDrugId;
    public $amount = 1;

    public function sell(GameFacade $game)
    {
        if ($this->amount <= 0) {
            $this->dispatch('toast', type: 'error', message: 'Informe uma quantidade válida!');
            return;
        }

        try {
            $drug = DrugModel::findOrFail($this->selectedDrugId);
            $game->action()->sell($drug, $this->amount);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Drogas vendidas com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function rewardItem(GameFacade $game)
    {
        try {
            $drug = DrugModel::findOrFail($this->selectedDrugId);
            $game->action()->rewardItem($drug, $this->amount);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Drogas de recompensa adicionadas!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        $drugs = DrugModel::orderBy('name')->get();

        return view('livewire.game.drug', [
            'drugs' => $drugs,
        ]);
    }
}
