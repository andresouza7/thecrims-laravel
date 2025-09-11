<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\User;
use App\Services\MarketService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DrugController extends Controller
{
    public function index()
    {
        $user = User::first();
        $drugs = $user->drugs;

        return Inertia::render('game/Drug', ['drugs' => $drugs]);
    }

    public function sellDrug(Drug $drug, Request $request, MarketService $service)
    {
        $request->validate([
            'amount' => 'required'
        ]);

        try {
            $service->sell($drug, $request->amount);
            return redirect()->back()->with('message', 'drogas vendidas!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
