<?php

namespace App\Http\Controllers;

use App\Models\Boat;
use App\Models\Drug;
use App\Services\DockService;
use App\Services\MarketService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BoatController extends Controller
{
    public function index()
    {
        $data = DockService::getBoatData();

        return Inertia::render('game/Docks', ['data' => $data]);
    }

    public function sell(Boat $boat, Request $request, DockService $service, MarketService $market)
    {
        $request->validate([
            'amount' => 'required',
        ]);

        try {
            $boat->drug->validateInventory($service->user);
            $service->sellToBoat($boat, $request->amount, $market);
            redirect()->back()->with('message', 'drogas vendidas para o barco!');
        } catch (\Throwable $th) {
            redirect()->back()->with('error', $th->getMessage());
        }
    }
}
