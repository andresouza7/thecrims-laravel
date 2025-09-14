<?php

namespace App\Http\Controllers;

use App\Services\GameFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HospitalController extends Controller
{
    public function index()
    {
        return Inertia::render('game/Hospital');
    }

    public function release(GameFacade $game)
    {
        handleRequest(fn() => $game->action()->releaseFromHospital(), 'Você está saudável de novo!');
    }
}
