<?php

namespace App\Livewire\Game;

use App\Services\GameFacade;
use Livewire\Component;

class Hospital extends Component
{
    public function release(GameFacade $game)
    {
        try {
            $game->action()->releaseFromHospital();
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Você está saudável de novo!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.game.hospital');
    }
}
