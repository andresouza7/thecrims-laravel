<?php

namespace App\Http\Controllers;

use App\Services\GameFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JailController extends Controller
{
    public function index()
    {
        return Inertia::render('game/Jail');
    }

    public function release(GameFacade $game)
    {
        handleRequest(fn() => $game->action()->releaseFromJail(), 'Você está livre de novo!');
    }
}
