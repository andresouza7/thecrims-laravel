<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use App\Models\User;
use App\Models\UserFactory;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FactoryController extends Controller
{
    public function index()
    {
        $factories = Factory::orderBy('name')->get();

        $user = User::first();
        $owned = $user->factories;
        return Inertia::render('game/Factory', ['factories' => $factories, 'owned' => $owned]);
    }

    public function buyFactory(Factory $factory, UserService $service)
    {
        try {
            $service->buy($factory, 1);
            return redirect()->back()->with('message', 'fabrica comprada!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function sellFactory(UserFactory $userFactory, UserService $service)
    {
        try {
            $service->sell($userFactory, 1);
            return redirect()->back()->with('message', 'fabrica vendida!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function upgradeFactory(UserFactory $userFactory, UserService $service)
    {
        try {
            $service->upgradeFactory($userFactory);
            return redirect()->back()->with('message', 'upgrade realizado!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
