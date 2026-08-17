<?php

namespace App\Livewire\Game;

use App\Models\Boat as BoatModel;
use App\Services\GameFacade;
use Livewire\Component;

class Boat extends Component
{
    public array $amounts = [];

    public function sell($boatId, GameFacade $game)
    {
        $amount = $this->amounts[$boatId] ?? 0;

        if ($amount <= 0) {
            session()->flash('error', 'Informe uma quantidade válida!');
            return;
        }

        try {
            $boat = BoatModel::findOrFail($boatId);
            $game->boat()->sellToBoat($boat, $amount);
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Drogas vendidas com sucesso para o navio!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        return view('livewire.game.boat', [
            'data' => $game->boat()->getBoatData(),
        ]);
    }
}
