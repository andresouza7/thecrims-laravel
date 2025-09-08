<?php

namespace App\Http\Controllers;

use App\Models\Hooker;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HookerController
{
    public function index()
    {
        $hookers = Hooker::orderBy('name')->get();

        $user = User::first();
        $owned = $user->hookers;
        return Inertia::render('game/Hooker', ['hookers' => $hookers, 'owned' => $owned]);
    }

    public function buyHooker(Hooker $hooker, Request $request, UserService $service)
    {
        $request->validate([
            'amount' => 'required'
        ]);

        try {
            $service->buy($hooker, $request->amount);
            return redirect()->back()->with('message', 'putas compradas!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function sellHooker(Hooker $hooker, Request $request, UserService $service)
    {
        $request->validate([
            'amount' => 'required'
        ]);

        try {
            $service->sell($hooker, $request->amount);
            return redirect()->back()->with('message', 'putas vendidas!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
