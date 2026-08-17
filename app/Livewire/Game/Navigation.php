<?php

namespace App\Livewire\Game;

use Livewire\Component;

class Navigation extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public function render()
    {
        return view('livewire.game.navigation');
    }
}
