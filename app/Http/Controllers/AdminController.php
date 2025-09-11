<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use App\Models\User;
use App\Models\UserFactory;
use App\Services\GameService;
use App\Services\MarketService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render('game/Admin');
    }

    public function createRound()
    {
        try {
            GameService::createRound();
            return redirect()->back()->with('message', 'novo round iniciado!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
