<?php

namespace App\Livewire\Game;

use App\Models\Boat as BoatModel;
use App\Services\GameFacade;
use Livewire\Component;
use Livewire\Attributes\On;

class Boat extends Component
{
    public array $amounts = [];

    protected $listeners = ['user-stats-updated' => '$refresh'];

    public function sell($boatId, GameFacade $game)
    {
        $amount = (int) ($this->amounts[$boatId] ?? 0);

        if ($amount <= 0) {
            $this->dispatch('toast', type: 'error', message: 'Informe uma quantidade válida!');
            return;
        }

        try {
            $boat = BoatModel::findOrFail($boatId);
            $game->boat()->sellToBoat($boat, $amount);
            $this->amounts[$boatId] = '';
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Drogas vendidas com sucesso para o navio!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        return view('livewire.game.boat', [
            'data' => $game->boat()->getBoatData(),
        ]);
    }
}
