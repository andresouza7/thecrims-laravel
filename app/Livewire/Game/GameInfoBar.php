<?php

namespace App\Livewire\Game;

use App\Services\GameService;
use Livewire\Component;

class GameInfoBar extends Component
{
    public function render()
    {
        return view('livewire.game.game-info-bar', [
            'gameDay' => GameService::getGameDay(),
            'gameTime' => GameService::getGameTime(),
        ]);
    }
}
