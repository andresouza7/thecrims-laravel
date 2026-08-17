<?php

namespace App\Livewire\Game;

use App\Models\CareerLevel;
use App\Models\Drug;
use App\Models\Equipment;
use App\Models\Hooker;
use App\Models\UserEquipment;
use App\Services\CareerService;
use App\Services\GameFacade;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DebugPanel extends Component
{
    public bool $isOpen = false;

    // User direct stats
    public $cash;
    public $bank;
    public $strength;
    public $tolerance;
    public $charisma;
    public $intelligence;
    public $stamina;

    // Drug management
    public $selectedDrugId;
    public $drugAmount;
    public $drugTotalSold;

    // Hooker management
    public $selectedHookerId;
    public $hookerAmount;

    // Equipment management
    public $selectedEquipmentId;

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->loadCurrentValues();
        }
    }

    public function loadCurrentValues()
    {
        $user = auth()->user() ?? \App\Models\User::first();
        if (!$user) return;

        $this->cash = $user->cash;
        $this->bank = $user->bank;
        $this->strength = $user->strength;
        $this->tolerance = $user->tolerance;
        $this->charisma = $user->charisma;
        $this->intelligence = $user->intelligence;
        $this->stamina = $user->stamina;

        if ($this->selectedDrugId) {
            $userDrug = DB::table('user_drugs')->where('user_id', $user->id)->where('drug_id', $this->selectedDrugId)->first();
            $this->drugAmount = $userDrug ? $userDrug->amount : 0;
            $this->drugTotalSold = $userDrug ? $userDrug->total_sold : 0;
        }

        if ($this->selectedHookerId) {
            $userHooker = DB::table('user_hookers')->where('user_id', $user->id)->where('hooker_id', $this->selectedHookerId)->first();
            $this->hookerAmount = $userHooker ? $userHooker->amount : 0;
        }
    }

    public function updatedSelectedDrugId($val)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        if ($val && $user) {
            $userDrug = DB::table('user_drugs')->where('user_id', $user->id)->where('drug_id', $val)->first();
            $this->drugAmount = $userDrug ? $userDrug->amount : 0;
            $this->drugTotalSold = $userDrug ? $userDrug->total_sold : 0;
        }
    }

    public function updatedSelectedHookerId($val)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        if ($val && $user) {
            $userHooker = DB::table('user_hookers')->where('user_id', $user->id)->where('hooker_id', $val)->first();
            $this->hookerAmount = $userHooker ? $userHooker->amount : 0;
        }
    }

    public function updateStats(GameFacade $game)
    {
        $user = $game->user;
        $user->cash = (int) $this->cash;
        $user->bank = (int) $this->bank;
        $user->strength = (int) $this->strength;
        $user->tolerance = (int) $this->tolerance;
        $user->charisma = (int) $this->charisma;
        $user->intelligence = (int) $this->intelligence;
        $user->stamina = min(100, max(0, (int) $this->stamina));
        $user->save();

        $this->dispatch('user-stats-updated');
        $this->dispatch('toast', type: 'info', message: 'Atributos salvos!');
    }

    public function updateDrug(GameFacade $game)
    {
        if (!$this->selectedDrugId) return;

        DB::table('user_drugs')->updateOrInsert(
            ['user_id' => $game->user->id, 'drug_id' => $this->selectedDrugId],
            [
                'amount' => max(0, (int) $this->drugAmount),
                'total_sold' => max(0, (int) $this->drugTotalSold),
                'updated_at' => now(),
            ]
        );

        $this->dispatch('user-stats-updated');
        $this->dispatch('toast', type: 'info', message: 'Droga atualizada!');
    }

    public function updateHooker(GameFacade $game)
    {
        if (!$this->selectedHookerId) return;

        DB::table('user_hookers')->updateOrInsert(
            ['user_id' => $game->user->id, 'hooker_id' => $this->selectedHookerId],
            [
                'amount' => max(0, (int) $this->hookerAmount),
                'updated_at' => now(),
            ]
        );

        $this->dispatch('user-stats-updated');
        $this->dispatch('toast', type: 'info', message: 'Prostituta atualizada!');
    }

    public function toggleEquipment(GameFacade $game)
    {
        if (!$this->selectedEquipmentId) return;

        $existing = UserEquipment::where('user_id', $game->user->id)->where('equipment_id', $this->selectedEquipmentId)->first();
        if ($existing) {
            $existing->delete();
            $this->dispatch('toast', type: 'info', message: 'Equipamento removido!');
        } else {
            UserEquipment::create([
                'user_id' => $game->user->id,
                'equipment_id' => $this->selectedEquipmentId,
            ]);
            $this->dispatch('toast', type: 'info', message: 'Equipamento adicionado!');
        }

        $this->dispatch('user-stats-updated');
    }

    public function autoFulfillNextLevelRequirements(GameFacade $game, CareerService $careerService)
    {
        $user = $game->user;
        if (!$user->career_id) return;

        $currentLevelNum = $careerService->getUserCurrentLevelNumber($user);
        $nextLevel = CareerLevel::where('career_id', $user->career_id)->where('level', $currentLevelNum + 1)->first();

        if (!$nextLevel) {
            $this->dispatch('toast', type: 'info', message: 'Usuário já está no nível máximo!');
            return;
        }

        $requirements = $nextLevel->getRequirements();

        foreach ($requirements as $clp) {
            $param = $clp->game_param;
            if (!$param) continue;

            $needed = (int) $clp->value;

            switch ($param->name) {
                case 'respect':
                    $user->user_respect = max((int)$user->user_respect, $needed);
                    break;
                case 'cash':
                    $user->cash = max((int)$user->cash, $needed);
                    break;
                case 'bank':
                    $user->bank = max((int)$user->bank, $needed);
                    break;
                case 'strength':
                    $user->strength = max((int)$user->strength, $needed);
                    break;
                case 'intelligence':
                    $user->intelligence = max((int)$user->intelligence, $needed);
                    break;
                case 'charisma':
                    $user->charisma = max((int)$user->charisma, $needed);
                    break;
                case 'tolerance':
                    $user->tolerance = max((int)$user->tolerance, $needed);
                    break;
                case 'drug_sold':
                case 'drug_produced':
                    if ($param->target_type === 'drug' && $param->target_id) {
                        DB::table('user_drugs')->updateOrInsert(
                            ['user_id' => $user->id, 'drug_id' => $param->target_id],
                            ['total_sold' => $needed, 'updated_at' => now()]
                        );
                    }
                    break;
                case 'equipment_owned':
                    if ($param->target_type === 'equipment' && $param->target_id) {
                        UserEquipment::firstOrCreate([
                            'user_id' => $user->id,
                            'equipment_id' => $param->target_id,
                        ]);
                    }
                    break;
                case 'hookers_count':
                    $currentHookersCount = DB::table('user_hookers')->where('user_id', $user->id)->sum('amount');
                    if ($currentHookersCount < $needed) {
                        $hooker = \App\Models\Hooker::first();
                        if ($hooker) {
                            $diff = $needed - $currentHookersCount;
                            $userHooker = DB::table('user_hookers')->where('user_id', $user->id)->where('hooker_id', $hooker->id)->first();
                            if ($userHooker) {
                                DB::table('user_hookers')->where('id', $userHooker->id)->increment('amount', $diff);
                            } else {
                                DB::table('user_hookers')->insert([
                                    'user_id' => $user->id,
                                    'hooker_id' => $hooker->id,
                                    'amount' => $diff,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    }
                    break;
                case 'factories_count':
                    $currentFactoriesCount = DB::table('user_factories')->where('user_id', $user->id)->count();
                    if ($currentFactoriesCount < $needed) {
                        $factory = \App\Models\Factory::first();
                        if ($factory) {
                            for ($i = 0; $i < ($needed - $currentFactoriesCount); $i++) {
                                DB::table('user_factories')->insert([
                                    'user_id' => $user->id,
                                    'factory_id' => $factory->id,
                                    'level' => 1,
                                    'stash' => 0,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    }
                    break;
                default:
                    if ($param->target_type === 'drug' && $param->target_id) {
                        DB::table('user_drugs')->updateOrInsert(
                            ['user_id' => $user->id, 'drug_id' => $param->target_id],
                            ['amount' => $needed, 'updated_at' => now()]
                        );
                    }
                    break;
            }
        }

        $user->save();
        $this->loadCurrentValues();
        $this->dispatch('user-stats-updated');
        $this->dispatch('toast', type: 'info', message: "Requisitos do Nível " . ($currentLevelNum + 1) . " preenchidos!");
    }

    public function resetCareerLevel(GameFacade $game)
    {
        $user = $game->user;
        if ($user->career_id) {
            $level1 = CareerLevel::where('career_id', $user->career_id)->where('level', 1)->first();
            $user->career_level_id = $level1?->id;
            $user->save();
            $this->dispatch('user-stats-updated');
            session()->flash('debug_msg', 'Carreira resetada para Nível 1!');
        }
    }

    public function render()
    {
        $drugs = Drug::orderBy('name')->get();
        $hookers = Hooker::orderBy('name')->get();
        $equipment = Equipment::orderBy('name')->get();
        $userEquipmentIds = auth()->check() ? auth()->user()->equipment()->pluck('equipment_id')->toArray() : [];

        return view('livewire.game.debug-panel', [
            'drugs' => $drugs,
            'hookers' => $hookers,
            'equipment' => $equipment,
            'userEquipmentIds' => $userEquipmentIds,
        ]);
    }
}
