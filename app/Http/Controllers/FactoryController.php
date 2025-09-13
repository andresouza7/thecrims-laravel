<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use App\Models\LabProduction;
use App\Models\UserFactory;
use App\Services\GameFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FactoryController extends Controller
{
    public function index(GameFacade $game)
    {
        $factories = Factory::with('drug')->orderBy('name')->get();
        $owned = $game->user->factories;

        return Inertia::render('game/Factory', compact('factories', 'owned'));
    }

    public function showFactory(UserFactory $userFactory)
    {
        $userFactory->load(['factory.drug', 'productions']);

        $lab = $userFactory;
        $components = $userFactory->user->components;

        return Inertia::render('game/Lab', compact('lab', 'components'));
    }

    public function buyFactory(Factory $factory, GameFacade $game)
    {
        handleRequest(fn() => $game->action()->buy($factory), 'fabrica comprada!');
    }

    public function sellFactory(UserFactory $userFactory, GameFacade $game)
    {
        handleRequest(fn() => $game->action()->sell($userFactory), 'fabrica vendida!');
    }

    public function upgradeFactory(UserFactory $userFactory, GameFacade $game)
    {
        handleRequest(fn() => $game->action()->upgradeFactory($userFactory), 'upgrade realizado!');
    }

    public function createLabProduction(UserFactory $userFactory, Request $request, GameFacade $game)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
            'component_id' => 'required|exists:components,id',
        ]);

        handleRequest(
            fn() => $game->action()->createLabProduction($userFactory, $request->component_id, $request->amount),
            'produção iniciada!'
        );
    }

    public function cancelLabProduction(LabProduction $production, GameFacade $game)
    {
        handleRequest(fn() => $game->action()->cancelLabProduction($production), 'produção cancelada!');
    }

    public function claimLabProduction(LabProduction $production, GameFacade $game)
    {
        handleRequest(fn() => $game->action()->claimLabProduction($production), 'drogas coletadas!');
    }

    public function collectProduction(GameFacade $game)
    {
        handleRequest(fn() => $game->action()->collectFactoryProduction(), 'produção coletada!');
    }
}
