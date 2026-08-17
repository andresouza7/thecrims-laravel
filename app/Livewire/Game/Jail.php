<?php

namespace App\Livewire\Game;

use App\Services\GameFacade;
use Livewire\Component;

class Jail extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];
    public function bribe(GameFacade $game)
    {
        try {
            $game->action()->bribeJailGuard();
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Você pagou o do lanche e está livre por enquanto!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function release(GameFacade $game)
    {
        try {
            $game->action()->releaseFromJail();
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Você está livre de novo!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.game.jail');
    }
}
