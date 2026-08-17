<?php

namespace App\Livewire\Game;

use App\Services\GameFacade;
use Livewire\Component;

class Jail extends Component
{
    public function bribe(GameFacade $game)
    {
        try {
            $game->action()->bribeJailGuard();
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Você pagou o do lanche e está livre por enquanto!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function release(GameFacade $game)
    {
        try {
            $game->action()->releaseFromJail();
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Você está livre de novo!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.game.jail');
    }
}
