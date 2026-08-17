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
            $game->action()->buy($factory);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Fábrica comprada com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function sellFactory($userFactoryId, GameFacade $game)
    {
        try {
            $userFactory = UserFactory::findOrFail($userFactoryId);
            $game->action()->sell($userFactory);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Fábrica vendida com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function upgradeFactory($userFactoryId, GameFacade $game)
    {
        try {
            $userFactory = UserFactory::findOrFail($userFactoryId);
            $game->action()->upgradeFactory($userFactory);
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Fábrica atualizada com sucesso!');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function collectProduction(GameFacade $game)
    {
        try {
            $game->action()->collectFactoryProduction();
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Unidades coletadas com sucesso!');
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
