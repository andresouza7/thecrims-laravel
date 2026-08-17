<?php

namespace App\Livewire\Game;

use App\Models\UserEquipment;
use App\Services\GameFacade;
use Livewire\Component;

class Inventory extends Component
{
    public function activate($userEquipmentId, GameFacade $game)
    {
        try {
            $equipment = UserEquipment::findOrFail($userEquipmentId);
            $game->action()->activateEquipment($equipment);
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Equipamento equipado com sucesso!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function deactivate($userEquipmentId, GameFacade $game)
    {
        try {
            $equipment = UserEquipment::findOrFail($userEquipmentId);
            $game->action()->deactivateEquipment($equipment);
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Equipamento desequipado com sucesso!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function sell($userEquipmentId, GameFacade $game)
    {
        try {
            $equipment = UserEquipment::findOrFail($userEquipmentId);
            $game->action()->sell($equipment);
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Equipamento vendido com sucesso!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        $armors = $game->user->equipment()->where('type', 'armor')->get();
        $weapons = $game->user->equipment()->whereNot('type', 'armor')->get();

        return view('livewire.game.inventory', [
            'armors' => $armors,
            'weapons' => $weapons,
        ]);
    }
}
