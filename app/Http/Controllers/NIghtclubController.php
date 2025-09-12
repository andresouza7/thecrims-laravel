<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NightclubController extends Controller
{
    public function index()
    {
        $user = User::first();

        $foe = User::whereNot('id', $user->id)
            ->inRandomOrder()
            ->first();

        return Inertia::render('game/Nightclub', ['foe' => $foe]);
    }

    public function fight(User $user, UserService $service)
    {
        try {
            $result = $service->fight($user);

            if ($result['loser'] === $user->id) {
                return redirect()->back()->with('success', 'Você venceu!');
            }

            return redirect()->back()->with('error', 'Você perdeu!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
