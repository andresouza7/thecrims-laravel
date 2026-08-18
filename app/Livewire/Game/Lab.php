<?php

namespace App\Livewire\Game;

use App\Models\LabProduction;
use App\Models\UserFactory;
use App\Services\GameFacade;
use Livewire\Component;

class Lab extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public UserFactory $userFactory;
    public $component_id;
    public $amount = 1;

    public function mount(UserFactory $userFactory)
    {
        if (!$userFactory->factory || !$userFactory->factory->is_lab) {
            abort(403);
        }
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
        $components = $this->userFactory->user->components()->with('drug')->get();

        $requiredComponents = 0;
        $estimatedDuration = 0;
        $componentsPerUnit = 0;
        $maxProduceableDrugs = 0;
        $selectedDrugName = '';

        if ($this->component_id && $this->amount > 0) {
            $selectedComponent = $components->firstWhere('id', $this->component_id);
            if ($selectedComponent && $selectedComponent->drug) {
                $componentsPerUnit = $selectedComponent->drug->getComponentsPerUnit();
                $requiredComponents = $this->amount * $componentsPerUnit;
                $selectedDrugName = $selectedComponent->drug->name;

                $playerComponentAmount = $selectedComponent->pivot ? $selectedComponent->pivot->amount : 0;
                $maxProduceableDrugs = (int) floor($playerComponentAmount / $componentsPerUnit);

                $scaledAmount = (int) max(1, $requiredComponents / 1000);
                $total = 2 + (int) floor(sqrt($scaledAmount));
                $factor = max(0.2, 1.0 / $this->userFactory->level);
                $estimatedDuration = (int) max(1, (int) round($total * $factor));
            }
        }

        return view('livewire.game.lab', [
            'lab' => $this->userFactory,
            'components' => $components,
            'requiredComponents' => $requiredComponents,
            'maxProduceableDrugs' => $maxProduceableDrugs,
            'estimatedDuration' => $estimatedDuration,
            'componentsPerUnit' => $componentsPerUnit,
            'selectedDrugName' => $selectedDrugName,
        ]);
    }
}
