<?php

namespace App\Livewire\Game;

use App\Services\GameService;
use Livewire\Component;

class GameInfoBar extends Component
{
    public int $lastKnownDay = 0;

    public function mount()
    {
        $this->lastKnownDay = GameService::getGameDay();
    }

    public function render()
    {
        $currentDay = GameService::getGameDay();

        if ($this->lastKnownDay > 0 && $currentDay !== $this->lastKnownDay) {
            $this->lastKnownDay = $currentDay;
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'info', message: "🗓️ O Dia {$currentDay} começou! Rendimentos, juros e produções foram atualizados.");
        } else {
            $this->lastKnownDay = $currentDay;
        }

        return view('livewire.game.game-info-bar', [
            'gameDay' => $currentDay,
            'gameTime' => GameService::getGameTime(),
        ]);
    }
}
