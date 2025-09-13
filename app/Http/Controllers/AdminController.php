<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render('game/Admin');
    }

    public function createRound()
    {
        handleRequest(fn() => GameService::createRound(), 'novo round iniciado!');
    }
}
