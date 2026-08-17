<?php

namespace App\Livewire\Game\Market;

use App\Models\Equipment;
use App\Services\GameFacade;
use Livewire\Component;

class Index extends Component
{
    public string $tab = 'armors';

    public function buy($equipmentId, GameFacade $game)
    {
        try {
            $equipment = Equipment::findOrFail($equipmentId);
            $totalCost = $game->action()->buy($equipment);
            $formattedTotal = number_format($totalCost);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: "Equipamento {$equipment->name} comprado por \${$formattedTotal} com sucesso!");
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render()
    {
        $armors = Equipment::where('type', 'armor')->orderBy('price')->get();
        $weapons = Equipment::whereNot('type', 'armor')->orderBy('price')->get();

        return view('livewire.game.market.index', [
            'armors' => $armors,
            'weapons' => $weapons,
        ]);
    }
}
