<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BankController extends Controller
{
    public function index() {
        return Inertia::render('game/Bank');
    }

    public function deposit(Request $request) {
        $user = User::first();
        $service = new UserService($user);

        $service->deposit($request->amount);

        return redirect()->back();
    }

    public function withdraw(Request $request) {
        $user = User::first();
        $service = new UserService($user);

        $service->withdraw($request->amount);

        return redirect()->back();
    }
}
