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
        session()->flash('debug_msg', 'Atributos salvos!');
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
        session()->flash('debug_msg', 'Droga atualizada!');
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
        session()->flash('debug_msg', 'Prostituta atualizada!');
    }

    public function toggleEquipment(GameFacade $game)
    {
        if (!$this->selectedEquipmentId) return;

        $existing = UserEquipment::where('user_id', $game->user->id)->where('equipment_id', $this->selectedEquipmentId)->first();
        if ($existing) {
            $existing->delete();
            session()->flash('debug_msg', 'Equipamento removido!');
        } else {
            UserEquipment::create([
                'user_id' => $game->user->id,
                'equipment_id' => $this->selectedEquipmentId,
            ]);
            session()->flash('debug_msg', 'Equipamento adicionado!');
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
            session()->flash('debug_msg', 'Usuário já está no nível máximo!');
            return;
        }

        $requirements = $nextLevel->getRequirements();

        foreach ($requirements as $clp) {
            $param = $clp->game_param;
            $target = $param?->target;
            $needed = $clp->value;

            switch ($param->name) {
                case 'cash':
                    $user->cash = max($user->cash, $needed);
                    break;
                case 'respect':
                    // Respect is calculated from cash / stats
                    $user->cash = max($user->cash, $needed * 30000);
                    break;
                case 'stats_total':
                    $each = (int) ceil($needed / 4);
                    $user->strength = max($user->strength, $each);
                    $user->tolerance = max($user->tolerance, $each);
                    $user->charisma = max($user->charisma, $each);
                    $user->intelligence = max($user->intelligence, $each);
                    break;
                case 'drug_sold':
                    if ($target instanceof Drug) {
                        DB::table('user_drugs')->updateOrInsert(
                            ['user_id' => $user->id, 'drug_id' => $target->id],
                            ['total_sold' => $needed, 'updated_at' => now()]
                        );
                    }
                    break;
                case 'equipment_owned':
                    if ($target instanceof Equipment) {
                        UserEquipment::firstOrCreate([
                            'user_id' => $user->id,
                            'equipment_id' => $target->id,
                        ]);
                    }
                    break;
                case 'hookers_count':
                    $hooker = Hooker::first();
                    if ($hooker) {
                        $userHooker = DB::table('user_hookers')->where('user_id', $user->id)->where('hooker_id', $hooker->id)->first();
                        $currentTotal = DB::table('user_hookers')->where('user_id', $user->id)->sum('amount');
                        if ($currentTotal < $needed) {
                            $diff = $needed - $currentTotal;
                            $newAmount = ($userHooker ? $userHooker->amount : 0) + $diff;
                            DB::table('user_hookers')->updateOrInsert(
                                ['user_id' => $user->id, 'hooker_id' => $hooker->id],
                                ['amount' => $newAmount, 'updated_at' => now()]
                            );
                        }
                    }
                    break;
                case 'hooker_type_owned':
                    if ($target instanceof Hooker) {
                        DB::table('user_hookers')->updateOrInsert(
                            ['user_id' => $user->id, 'hooker_id' => $target->id],
                            ['amount' => $needed, 'updated_at' => now()]
                        );
                    }
                    break;
            }
        }

        $user->save();
        $this->loadCurrentValues();
        $this->dispatch('user-stats-updated');
        session()->flash('debug_msg', "Requisitos do Nível " . ($currentLevelNum + 1) . " preenchidos!");
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
