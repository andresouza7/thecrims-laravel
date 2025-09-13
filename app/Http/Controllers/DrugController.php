<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Services\GameFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DrugController extends Controller
{
    public function index(GameFacade $game)
    {
        return Inertia::render('game/Drug', ['drugs' => $game->user->drugs]);
    }

    public function reward(GameFacade $game)
    {
        $drug = Drug::inRandomOrder()->first();

        handleRequest(fn() => $game->action()->rewardItem($drug, 200), 'drugs added to user!');
    }

    public function sell(Drug $drug, Request $request, GameFacade $game)
    {
        $request->validate([
            'amount' => 'required'
        ]);

        handleRequest(fn() => $game->action()->sell($drug, $request->amount), 'drogas vendidas!');
    }
}
