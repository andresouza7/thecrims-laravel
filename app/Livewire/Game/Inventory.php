<?php

namespace App\Livewire\Game;

use App\Models\UserEquipment;
use App\Services\GameFacade;
use Livewire\Component;

class Inventory extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public function activate($userEquipmentId, GameFacade $game)
    {
        try {
            $userEquipment = UserEquipment::where('user_id', $game->user->id)->findOrFail($userEquipmentId);
            $game->action()->activateEquipment($userEquipment);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Equipamento equipado com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function deactivate($userEquipmentId, GameFacade $game)
    {
        try {
            $userEquipment = UserEquipment::where('user_id', $game->user->id)->findOrFail($userEquipmentId);
            $game->action()->deactivateEquipment($userEquipment);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Equipamento desequipado com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function sell($userEquipmentId, GameFacade $game)
    {
        try {
            $userEquipment = UserEquipment::where('user_id', $game->user->id)->findOrFail($userEquipmentId);
            $game->action()->sell($userEquipment);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Equipamento vendido com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        $user = $game->user;
        $user->unsetRelation('equipment');
        $user->refresh();

        $armors = $user->equipment()->where('type', 'armor')->get();
        $weapons = $user->equipment()->whereNot('type', 'armor')->get();

        return view('livewire.game.inventory', [
            'armors' => $armors,
            'weapons' => $weapons,
            'user' => $user,
        ]);
    }
}
