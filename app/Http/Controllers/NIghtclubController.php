<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GameFacade;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NightclubController extends Controller
{
    public function index(GameFacade $game)
    {
        $foe = User::whereNot('id', $game->user->id)
            ->inRandomOrder()
            ->first();

        return Inertia::render('game/Nightclub', ['foe' => $foe]);
    }

    public function fight(User $user, GameFacade $game)
    {
        try {
            $result = $game->action()->fight($user);

            if ($result['loser'] === $user->id) {
                return redirect()->back()->with('success', 'Você venceu!');
            }

            return redirect()->back()->with('error', 'Você perdeu!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
