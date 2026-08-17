<?php

namespace App\Livewire\Game;

use App\Services\GameFacade;
use Livewire\Component;

class UserHeader extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public function render(GameFacade $game)
    {
        return view('livewire.game.user-header', [
            'user' => $game->user,
        ]);
    }
}
