<?php

namespace App\Livewire\Game;

use App\Models\Factory as FactoryModel;
use App\Models\UserFactory;
use App\Services\GameFacade;
use Livewire\Component;

class Factory extends Component
{
    public function buyFactory($factoryId, GameFacade $game)
    {
        try {
            $factory = FactoryModel::findOrFail($factoryId);
            $totalCost = $game->action()->buy($factory);
            $formattedTotal = number_format($totalCost);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: "Fábrica {$factory->name} comprada por \${$formattedTotal} com sucesso!");
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function sellFactory($userFactoryId, GameFacade $game)
    {
        try {
            $userFactory = UserFactory::findOrFail($userFactoryId);
            $factoryName = $userFactory->factory->name;
            $totalProfit = $game->action()->sell($userFactory);
            $formattedTotal = number_format($totalProfit);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: "Fábrica {$factoryName} vendida por \${$formattedTotal} com sucesso!");
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function upgradeFactory($userFactoryId, GameFacade $game)
    {
        try {
            $userFactory = UserFactory::findOrFail($userFactoryId);
            $cost = $game->action()->upgradeFactory($userFactory);
            $formattedCost = number_format($cost);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: "Fábrica evoluída por \${$formattedCost} com sucesso!");
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function collectProduction(GameFacade $game)
    {
        try {
            $game->action()->collectFactoryProduction();
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Estoque da fábrica coletado com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        return view('livewire.game.factory', [
            'factories' => FactoryModel::with('drug')->orderBy('name')->get(),
            'owned' => $game->user->factories,
        ]);
    }
}
