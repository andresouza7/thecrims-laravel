<?php

namespace App\Livewire\Game;

use App\Services\GameFacade;
use Livewire\Component;

class Bank extends Component
{
    public $amount = '';

    public function deposit(GameFacade $game)
    {
        $this->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            $game->action()->deposit($this->amount);
            $this->reset('amount');
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Depósito efetuado com sucesso!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function withdraw(GameFacade $game)
    {
        $this->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            $game->action()->withdraw($this->amount);
            $this->reset('amount');
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Saque realizado com sucesso!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.game.bank');
    }
}
