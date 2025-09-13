<?php

namespace App\Http\Controllers;

use App\Models\Boat;
use App\Services\GameFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BoatController extends Controller
{
    public function index(GameFacade $game)
    {
        $data = $game->boat()->getBoatData();

        return Inertia::render('game/Docks', ['data' => $data]);
    }

    public function sell(Boat $boat, Request $request, GameFacade $game)
    {
        $request->validate([
            'amount' => 'required',
        ]);

        handleRequest(
            fn() => $game->boat()->sellToBoat($boat, $request->amount),
            'drogas vendidas para o barco'
        );
    }
}
