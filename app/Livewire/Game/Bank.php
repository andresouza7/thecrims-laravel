<?php

namespace App\Livewire\Game;

use App\Services\GameFacade;
use Livewire\Component;

class Bank extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public string $operation = 'deposit';
    public $amount = 0;

    public function executeTransaction(GameFacade $game)
    {
        $this->validate([
            'operation' => 'required|in:deposit,withdraw',
            'amount' => 'nullable|numeric|min:0',
        ]);

        try {
            $user = $game->user;
            $val = (int) ($this->amount ?: 0);

            if ($this->operation === 'deposit') {
                if ($val <= 0) {
                    $val = $user->cash;
                }

                $game->action()->deposit($val);
                $this->amount = 0;
                $this->dispatch('user-stats-updated');
                $this->dispatch('toast', type: 'success', message: "Depósito de $" . number_format($val) . " efetuado com sucesso!");
            } else {
                if ($val <= 0) {
                    $val = $user->bank;
                }

                $game->action()->withdraw($val);
                $this->amount = 0;
                $this->dispatch('user-stats-updated');
                $this->dispatch('toast', type: 'success', message: "Saque de $" . number_format($val) . " realizado com sucesso!");
            }
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        return view('livewire.game.bank', [
            'user' => $game->user,
        ]);
    }
}
