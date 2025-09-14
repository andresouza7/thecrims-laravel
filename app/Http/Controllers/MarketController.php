<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Services\GameFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarketController extends Controller
{
    public function index()
    {
        return Inertia::render('game/market/index');
    }

    public function components()
    {
        return Inertia::render('game/market/ComponentsList');
    }

    public function componentsAuction()
    {
        return Inertia::render('game/market/ComponentsAuction');
    }

    public function armors()
    {
        $armors = Equipment::where('type', 'armor')->orderBy('price')->get();

        return Inertia::render('game/market/Armors', compact('armors'));
    }

    public function weapons()
    {
        $weapons = Equipment::whereNot('type', 'armor')->orderBy('price')->get();

        return Inertia::render('game/market/Weapons', compact('weapons'));
    }

    public function items()
    {
        return Inertia::render('game/market/Items');
    }

    public function buy(Equipment $equipment, GameFacade $game)
    {
        handleRequest(fn() => $game->action()->buy($equipment), 'equipment successfully bought!');
    }
}
