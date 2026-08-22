<?php

namespace App\Livewire\Game;

use App\Services\GameFacade;
use Livewire\Component;

class Hospital extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

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

    public function buyDetox(GameFacade $game)
    {
        try {
            $game->action()->buyDetoxification();
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Tratamento de desintoxicação concluído com sucesso! Seu vício foi zerado.');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        $user = $game->user;
        $user->refresh();

        $detoxCost = $game->action()->calculateDetoxCost();

        return view('livewire.game.hospital', [
            'user' => $user,
            'detoxCost' => $detoxCost,
        ]);
    }
}
