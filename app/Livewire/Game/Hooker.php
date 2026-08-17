<?php

namespace App\Livewire\Game;

use App\Models\Hooker as HookerModel;
use App\Services\GameFacade;
use Livewire\Component;

class Hooker extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public array $buyAmounts = [];
    public array $sellAmounts = [];

    public function buyHooker($hookerId, GameFacade $game)
    {
        $amount = (int) ($this->buyAmounts[$hookerId] ?? 1);
        if ($amount <= 0) $amount = 1;

        try {
            $hooker = HookerModel::findOrFail($hookerId);
            $totalCost = $game->action()->buy($hooker, $amount);
            $formattedTotal = number_format($totalCost);
            $this->buyAmounts[$hookerId] = '';
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: "{$amount}x {$hooker->name} comprada(s) por \${$formattedTotal} com sucesso!");
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function sellHooker($hookerId, GameFacade $game)
    {
        $amount = (int) ($this->sellAmounts[$hookerId] ?? 1);
        if ($amount <= 0) $amount = 1;

        try {
            $hooker = HookerModel::findOrFail($hookerId);
            $totalProfit = $game->action()->sell($hooker, $amount);
            $formattedTotal = number_format($totalProfit);
            $this->sellAmounts[$hookerId] = '';
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: "{$amount}x {$hooker->name} vendida(s) por \${$formattedTotal} com sucesso!");
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function collectIncome(GameFacade $game)
    {
        try {
            $income = $game->action()->collectHookerIncome();
            $formattedIncome = number_format($income);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: "Renda de \${$formattedIncome} coletada com sucesso!");
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        $user = $game->user;
        $user->refresh();

        return view('livewire.game.hooker', [
            'user' => $user,
            'hookers' => HookerModel::orderBy('price', 'asc')->get(),
            'owned' => $user->hookers()->orderBy('price', 'asc')->get(),
        ]);
    }
}
