<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use App\Models\User;
use App\Models\UserFactory;
use App\Services\MarketService;
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

    public function showFactory(UserFactory $userFactory)
    {
        $userFactory->load('factory');
        $userFactory->load('factory.drug');

        return Inertia::render('game/Lab', ['lab' => $userFactory]);
    }

    public function buyFactory(Factory $factory, MarketService $service)
    {
        try {
            $service->buy($factory);
            return redirect()->back()->with('message', 'fabrica comprada!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function sellFactory(UserFactory $userFactory, MarketService $service)
    {
        try {
            $service->sell($userFactory);
            return redirect()->back()->with('message', 'fabrica vendida!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function upgradeFactory(UserFactory $userFactory, MarketService $service)
    {
        try {
            $service->upgradeFactory($userFactory);
            return redirect()->back()->with('message', 'upgrade realizado!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function collectProduction(UserService $service) {
        try {
            $service->collectFactoryProduction();
            return redirect()->back()->with('message', 'produção coletada!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
