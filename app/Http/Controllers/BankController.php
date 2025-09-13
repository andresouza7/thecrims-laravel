<?php

namespace App\Http\Controllers;

use App\Services\GameFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BankController extends Controller
{
    public function index()
    {
        return Inertia::render('game/Bank');
    }

    public function deposit(Request $request, GameFacade $game)
    {
        handleRequest(fn() => $game->action()->deposit($request->amount), 'depósito efetuado!');
    }

    public function withdraw(Request $request, GameFacade $game)
    {
        handleRequest(fn() =>  $game->action()->withdraw($request->amount), 'saque realizado!');
    }
}
