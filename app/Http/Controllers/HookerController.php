<?php

namespace App\Http\Controllers;

use App\Models\Hooker;
use App\Models\User;
use App\Services\GameFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HookerController
{
    public function index(GameFacade $game)
    {
        $hookers = Hooker::orderBy('name')->get();
        $owned = $game->user->hookers;

        return Inertia::render('game/Hooker', compact('hookers', 'owned'));
    }

    public function buyHooker(Hooker $hooker, Request $request, GameFacade $game)
    {
        $request->validate(['amount' => 'required']);

        handleRequest(fn() => $game->action()->buy($hooker, $request->amount), 'putas compradas!');
    }

    public function sellHooker(Hooker $hooker, Request $request, GameFacade $game)
    {
        $request->validate(['amount' => 'required']);

        handleRequest(fn() => $game->action()->sell($hooker, $request->amount), 'putas vendidas!');
    }

    public function collectIncome(GameFacade $game)
    {
        handleRequest(fn() => $game->action()->collectHookerIncome(), 'dinheiro coletado!');
    }
}
