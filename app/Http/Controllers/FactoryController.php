<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Factory;
use App\Models\LabProduction;
use App\Models\User;
use App\Models\UserFactory;
use App\Services\MarketService;
use App\Services\UserService;
use Carbon\Carbon;
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
        $userFactory->load(['factory.drug', 'productions']);

        $components = $userFactory->user->components;

        return Inertia::render('game/Lab', ['lab' => $userFactory, 'components' => $components]);
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

    public function createLabProduction(UserFactory $userFactory, Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
            'component_id' => 'required|exists:components,id',
        ]);

        try {
            $component = Component::findOrFail($request->component_id);
            $component->validateInventory($userFactory->user, $request->amount);

            // Consome os componentes
            $component->removeFromUser($userFactory->user, $request->amount);
            $drugId = $component->drug_id;

            // Tempo base por unidade
            $basePerUnit = 1; // 1 minuto por unidade (ajuste conforme regra)

            // Tempo total sem booster
            $totalDuration = $basePerUnit * $request->amount;

            // Booster do level → 5% mais rápido por nível (mínimo 20% do tempo)
            $reductionFactor = max(0.2, 1 - ($userFactory->level * 0.05));

            // Tempo final
            $duration = (int) round($totalDuration * $reductionFactor);

            LabProduction::create([
                'drug_id'        => $drugId,
                'user_factory_id' => $userFactory->id,
                'amount'         => $request->amount,
                'ends_at'        => Carbon::now()->addMinutes($duration),
            ]);

            return redirect()->back()->with('message', 'Produção iniciada!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function cancelLabProduction(LabProduction $production)
    {
        try {
            $production->delete();
            return redirect()->back()->with('message', 'produção cancelada!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function claimLabProduction(LabProduction $production, UserService $service)
    {
        try {
            // adicionar drogas na stath do player
            $drug = $production->drug;
            $amount = $production->amount;

            $user = $service->getUser();
            $drug->addToUser($user, $amount);

            $production->delete();

            return redirect()->back()->with('message', 'drogas coletadas!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function collectProduction(UserService $service)
    {
        try {
            $service->collectFactoryProduction();
            return redirect()->back()->with('message', 'produção coletada!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
