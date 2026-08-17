<?php

namespace App\Livewire\Game;

use App\Models\Hooker as HookerModel;
use App\Services\GameFacade;
use Livewire\Component;

class Hooker extends Component
{
    public array $amounts = [];

    public function buyHooker($hookerId, GameFacade $game)
    {
        $amount = $this->amounts[$hookerId] ?? 1;

        try {
            $hooker = HookerModel::findOrFail($hookerId);
            $game->action()->buy($hooker, $amount);
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Prostitutas compradas com sucesso!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function sellHooker($hookerId, GameFacade $game)
    {
        $amount = $this->amounts[$hookerId] ?? 1;

        try {
            $hooker = HookerModel::findOrFail($hookerId);
            $game->action()->sell($hooker, $amount);
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Prostitutas vendidas com sucesso!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function collectIncome(GameFacade $game)
    {
        try {
            $game->action()->collectHookerIncome();
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Renda das prostitutas coletada com sucesso!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
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
