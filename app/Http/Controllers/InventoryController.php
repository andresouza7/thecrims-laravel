<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\UserEquipment;
use App\Services\GameFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function index(GameFacade $game)
    {
        $armors = $game->user->equipment()->where('type', 'armor')->get();
        $weapons = $game->user->equipment()->whereNot('type', 'armor')->get();

        return Inertia::render('game/Inventory', compact('armors', 'weapons'));
    }

    public function activate(UserEquipment $equipment, GameFacade $game)
    {
        handleRequest(fn() => $game->action()->activateEquipment($equipment), 'equipment successfully activated!');
    }

    public function sell(UserEquipment $equipment, GameFacade $game)
    {
        handleRequest(fn() => $game->action()->sell($equipment), 'equipment successfully sold!');
    }
}
