<?php

namespace App\Livewire\Game;

use App\Models\Hooker as HookerModel;
use App\Services\GameFacade;
use Livewire\Component;

class Hooker extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public array $amounts = [];

    public function buyHooker($hookerId, GameFacade $game)
    {
        $amount = $this->amounts[$hookerId] ?? 1;

        try {
            $hooker = HookerModel::findOrFail($hookerId);
            $game->action()->buy($hooker, $amount);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Prostitutas compradas com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function sellHooker($hookerId, GameFacade $game)
    {
        $amount = $this->amounts[$hookerId] ?? 1;

        try {
            $hooker = HookerModel::findOrFail($hookerId);
            $game->action()->sell($hooker, $amount);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Prostitutas vendidas com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function collectIncome(GameFacade $game)
    {
        try {
            $game->action()->collectHookerIncome();
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Renda das prostitutas coletada com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        return view('livewire.game.hooker', [
            'hookers' => HookerModel::orderBy('name')->get(),
            'owned' => $game->user->hookers,
        ]);
    }
}
