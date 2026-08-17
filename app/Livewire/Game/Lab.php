<?php

namespace App\Livewire\Game;

use App\Models\LabProduction;
use App\Models\UserFactory;
use App\Services\GameFacade;
use Livewire\Component;

class Lab extends Component
{
    public UserFactory $userFactory;
    public $component_id;
    public $amount = 1;

    public function mount(UserFactory $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    public function createLabProduction(GameFacade $game)
    {
        $this->validate([
            'amount' => 'required|integer|min:1',
            'component_id' => 'required|exists:components,id',
        ]);

        try {
            $game->action()->createLabProduction($this->userFactory, $this->component_id, $this->amount);
            $this->reset(['amount', 'component_id']);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Produção iniciada no laboratório!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function cancelLabProduction($productionId, GameFacade $game)
    {
        try {
            $production = LabProduction::findOrFail($productionId);
            $game->action()->cancelLabProduction($production);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Produção cancelada!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function claimLabProduction($productionId, GameFacade $game)
    {
        try {
            $production = LabProduction::findOrFail($productionId);
            $game->action()->claimLabProduction($production);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Drogas coletadas com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render()
    {
        $this->userFactory->load(['factory.drug', 'productions.drug']);
        $components = $this->userFactory->user->components;

        return view('livewire.game.lab', [
            'lab' => $this->userFactory,
            'components' => $components,
        ]);
    }
}
