<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Services\GameFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::orderBy('name')->get();

        return Inertia::render('game/Career', compact('careers'));
    }

    public function select(Request $request, GameFacade $game)
    {
        $request->validate(['career_id' => 'required']);

        handleRequest(function () use ($game, $request) {
            $game->user->update(['career_id' => $request->career_id]);
        }, 'Carreira escolhida!');
    }

    public function about(GameFacade $game)
    {
        $career = $game->user->career()->with(['levels' => function ($query) {
            $query->orderBy('level'); // opcional, ordena por level
        }])->first();

        // Carrega requirements e rewards de cada level
        $career->levels->transform(function ($level) {
            return [
                'id' => $level->id,
                'level' => $level->level,
                'name' => $level->name,
                'requirements' => $level->getRequirements(), // método que você já definiu
                'rewards' => $level->getRewards(),           // método que você já definiu
            ];
        });

        return Inertia::render('game/About', compact('career'));
    }
}
