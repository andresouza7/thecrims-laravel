<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\CareerLevel;
use App\Models\CareerLevelParam;
use App\Models\GameParam;
use App\Models\Drug;
use App\Models\Equipment;
use App\Models\Hooker;
use App\Models\Component;
use Illuminate\Database\Seeder;

class CareerLevelParamSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch all target instances
        $drugs = Drug::orderBy('price', 'asc')->get(); // 10 drugs
        $equipments = Equipment::orderBy('price', 'asc')->get(); // 10 weapons + 10 armors
        $hookers = Hooker::orderBy('price', 'asc')->get(); // 10 hookers

        // Separate weapons and armors from equipment list
        $weapons = $equipments->filter(fn($e) => $e->type !== 'armor')->values();
        $armors = $equipments->filter(fn($e) => $e->type === 'armor')->values();

        // System generic params
        $reqCash = GameParam::where('name', 'cash')->where('type', 'requirement')->first();
        $reqRespect = GameParam::where('name', 'respect')->where('type', 'requirement')->first();
        $reqHookersCount = GameParam::where('name', 'hookers_count')->where('type', 'requirement')->first();
        $reqStatsTotal = GameParam::where('name', 'stats_total')->where('type', 'requirement')->first();
        $reqKillsCount = GameParam::where('name', 'kills_count')->where('type', 'requirement')->first();
        $reqSingleRobbery = GameParam::where('name', 'single_robbery_count')->where('type', 'requirement')->first();

        $rewCash = GameParam::where('name', 'cash')->where('type', 'reward')->first();
        $rewAvailableStats = GameParam::where('name', 'available_stats')->where('type', 'reward')->first();

        // 6 Career names
        $careerNames = [
            'Empresário',
            'Traficante',
            'Cafetão',
            'Assassino',
            'Ladrão',
            'Vigarista',
        ];

        // Cash rewards scaling from Level 2 to 10
        $cashRewards = [
            2 => 1000,
            3 => 3000,
            4 => 10000,
            5 => 35000,
            6 => 100000,
            7 => 350000,
            8 => 1200000,
            9 => 4500000,
            10 => 15000000,
        ];

        // Stats rewards scaling from Level 2 to 10
        $statsRewards = [
            2 => 10,
            3 => 30,
            4 => 100,
            5 => 300,
            6 => 1000,
            7 => 3500,
            8 => 10000,
            9 => 30000,
            10 => 100000,
        ];

        foreach ($careerNames as $cName) {
            $career = Career::where('name', $cName)->first();
            if (!$career) continue;

            $levels = CareerLevel::where('career_id', $career->id)->orderBy('level', 'asc')->get();

            foreach ($levels as $levelObj) {
                $lvl = $levelObj->level;
                if ($lvl <= 1) continue; // Level 1 has no requirements and rewards

                // 1. Grant Common Rewards (Cash & Stats Points)
                CareerLevelParam::create([
                    'career_level_id' => $levelObj->id,
                    'game_param_id' => $rewCash->id,
                    'value' => $cashRewards[$lvl],
                ]);

                CareerLevelParam::create([
                    'career_level_id' => $levelObj->id,
                    'game_param_id' => $rewAvailableStats->id,
                    'value' => $statsRewards[$lvl],
                ]);

                // 2. Career specific parameters
                switch ($cName) {
                    case 'Empresário':
                        // Requirements: Cash, stats_total (higher scale)
                        $reqCashVal = [2 => 2000, 3 => 10000, 4 => 50000, 5 => 200000, 6 => 1000000, 7 => 5000000, 8 => 20000000, 9 => 100000000, 10 => 500000000];
                        $reqStatsVal = [2 => 40, 3 => 150, 4 => 500, 5 => 1500, 6 => 5000, 7 => 15000, 8 => 50000, 9 => 150000, 10 => 500000];

                        CareerLevelParam::create([
                            'career_level_id' => $levelObj->id,
                            'game_param_id' => $reqCash->id,
                            'value' => $reqCashVal[$lvl],
                        ]);
                        CareerLevelParam::create([
                            'career_level_id' => $levelObj->id,
                            'game_param_id' => $reqStatsTotal->id,
                            'value' => $reqStatsVal[$lvl],
                        ]);

                        // Businessman theme rewards: component of drugs scaling up
                        if (in_array($lvl, [3, 4, 5, 6, 7])) {
                            $compIdx = $lvl - 3; // Maconha to Heroina
                            if (isset($drugs[$compIdx])) {
                                $drugObj = $drugs[$compIdx];
                                $comp = Component::where('drug_id', $drugObj->id)->first();
                                if ($comp) {
                                    $rewComp = GameParam::where('name', 'component_received')->where('target_type', Component::class)->where('target_id', $comp->id)->first();
                                    if ($rewComp) {
                                        CareerLevelParam::create([
                                            'career_level_id' => $levelObj->id,
                                            'game_param_id' => $rewComp->id,
                                            'value' => ($lvl - 1) * 10,
                                        ]);
                                    }
                                }
                            }
                        }
                        break;

                    case 'Traficante':
                        // Requirements: drug_sold (specific), cash
                        $drugSoldVal = [2 => 20, 3 => 50, 4 => 100, 5 => 200, 6 => 400, 7 => 800, 8 => 1500, 9 => 3000, 10 => 6000];
                        $reqCashVal = [2 => 2000, 3 => 5000, 4 => 20000, 5 => 80000, 6 => 300000, 7 => 1000000, 8 => 5000000, 9 => 20000000, 10 => 100000000];

                        // Determine drug target based on level
                        $drugIdx = min(9, $lvl - 2); // 0 to 8
                        $drugObj = $drugs[$drugIdx] ?? $drugs->first();

                        $gpReqDrugSold = GameParam::where('name', 'drug_sold')->where('target_type', Drug::class)->where('target_id', $drugObj->id)->first();
                        if ($gpReqDrugSold) {
                            CareerLevelParam::create([
                                'career_level_id' => $levelObj->id,
                                'game_param_id' => $gpReqDrugSold->id,
                                'value' => $drugSoldVal[$lvl],
                            ]);
                        }

                        CareerLevelParam::create([
                            'career_level_id' => $levelObj->id,
                            'game_param_id' => $reqCash->id,
                            'value' => $reqCashVal[$lvl],
                        ]);

                        // Dealer theme rewards: drug received
                        if (in_array($lvl, [3, 4, 5, 6])) {
                            $rewDrugIdx = $lvl - 3;
                            $rewDrugObj = $drugs[$rewDrugIdx] ?? $drugs->first();
                            $gpRewDrug = GameParam::where('name', 'drug_received')->where('target_type', Drug::class)->where('target_id', $rewDrugObj->id)->first();
                            if ($gpRewDrug) {
                                CareerLevelParam::create([
                                    'career_level_id' => $levelObj->id,
                                    'game_param_id' => $gpRewDrug->id,
                                    'value' => $lvl * 10,
                                ]);
                            }
                        }
                        break;

                    case 'Cafetão':
                        // Requirements: hookers_count, hooker_type_owned (specific for levels 5, 6, 9) and cash
                        $reqCashVal = [2 => 2000, 3 => 6000, 4 => 25000, 5 => 100000, 6 => 400000, 7 => 1500000, 8 => 6000000, 9 => 25000000, 10 => 100000000];

                        CareerLevelParam::create([
                            'career_level_id' => $levelObj->id,
                            'game_param_id' => $reqCash->id,
                            'value' => $reqCashVal[$lvl],
                        ]);

                        if (in_array($lvl, [5, 6, 9])) {
                            $hkIdx = min(9, $lvl - 3);
                            $hkObj = $hookers[$hkIdx] ?? $hookers->first();
                            $gpReqHkType = GameParam::where('name', 'hooker_type_owned')->where('target_type', Hooker::class)->where('target_id', $hkObj->id)->first();
                            if ($gpReqHkType) {
                                CareerLevelParam::create([
                                    'career_level_id' => $levelObj->id,
                                    'game_param_id' => $gpReqHkType->id,
                                    'value' => ($lvl === 9) ? 5 : ($lvl - 3),
                                ]);
                            }
                        } else {
                            $hkCountVal = [2 => 3, 3 => 6, 4 => 12, 7 => 30, 8 => 50, 10 => 100];
                            CareerLevelParam::create([
                                'career_level_id' => $levelObj->id,
                                'game_param_id' => $reqHookersCount->id,
                                'value' => $hkCountVal[$lvl],
                            ]);
                        }

                        // Pimp theme rewards: hooker received
                        if (in_array($lvl, [3, 4, 5, 6])) {
                            $rewHkIdx = $lvl - 3;
                            $rewHkObj = $hookers[$rewHkIdx] ?? $hookers->first();
                            $gpRewHk = GameParam::where('name', 'hooker_received')->where('target_type', Hooker::class)->where('target_id', $rewHkObj->id)->first();
                            if ($gpRewHk) {
                                CareerLevelParam::create([
                                    'career_level_id' => $levelObj->id,
                                    'game_param_id' => $gpRewHk->id,
                                    'value' => 1,
                                ]);
                            }
                        }
                        break;

                    case 'Assassino':
                        // Requirements: kills_count, stats_total (or equipment_active for 5, 6, 8, 9)
                        $killsVal = [2 => 1, 3 => 2, 4 => 4, 5 => 8, 6 => 15, 7 => 30, 8 => 60, 9 => 120, 10 => 250];

                        CareerLevelParam::create([
                            'career_level_id' => $levelObj->id,
                            'game_param_id' => $reqKillsCount->id,
                            'value' => $killsVal[$lvl],
                        ]);

                        if (in_array($lvl, [5, 6, 8, 9])) {
                            $wepIdx = min(9, $lvl - 2);
                            $wepObj = $weapons[$wepIdx] ?? $weapons->first();
                            $gpReqWepActive = GameParam::where('name', 'equipment_active')->where('target_type', Equipment::class)->where('target_id', $wepObj->id)->first();
                            if ($gpReqWepActive) {
                                CareerLevelParam::create([
                                    'career_level_id' => $levelObj->id,
                                    'game_param_id' => $gpReqWepActive->id,
                                    'value' => 1,
                                ]);
                            }
                        } else {
                            $statsVal = [2 => 50, 3 => 200, 4 => 700, 7 => 20000, 10 => 500000];
                            CareerLevelParam::create([
                                'career_level_id' => $levelObj->id,
                                'game_param_id' => $reqStatsTotal->id,
                                'value' => $statsVal[$lvl],
                            ]);
                        }

                        // Hitman theme rewards: equipment received (weapons or armors)
                        if (in_array($lvl, [3, 4, 5, 7, 9])) {
                            $rewWepIdx = $lvl - 3;
                            $rewWepObj = $weapons[$rewWepIdx] ?? $weapons->first();
                            $gpRewWep = GameParam::where('name', 'equipment_received')->where('target_type', Equipment::class)->where('target_id', $rewWepObj->id)->first();
                            if ($gpRewWep) {
                                CareerLevelParam::create([
                                    'career_level_id' => $levelObj->id,
                                    'game_param_id' => $gpRewWep->id,
                                    'value' => 1,
                                ]);
                            }
                        }
                        break;

                    case 'Ladrão':
                        // Requirements: single_robbery_count, respect (or cash for some levels)
                        $robVal = [2 => 10, 3 => 25, 4 => 60, 5 => 120, 6 => 250, 7 => 500, 8 => 1000, 9 => 2000, 10 => 5000];
                        $respVal = [2 => 0, 3 => 100, 4 => 300, 5 => 1000, 6 => 3000, 7 => 10000, 8 => 30000, 9 => 100000, 10 => 300000];

                        CareerLevelParam::create([
                            'career_level_id' => $levelObj->id,
                            'game_param_id' => $reqSingleRobbery->id,
                            'value' => $robVal[$lvl],
                        ]);

                        if ($lvl === 2) {
                            CareerLevelParam::create([
                                'career_level_id' => $levelObj->id,
                                'game_param_id' => $reqCash->id,
                                'value' => 2000,
                            ]);
                        } else {
                            CareerLevelParam::create([
                                'career_level_id' => $levelObj->id,
                                'game_param_id' => $reqRespect->id,
                                'value' => $respVal[$lvl],
                            ]);
                        }
                        break;

                    case 'Vigarista':
                        // Requirements: respect, stats_total, cash
                        $respVal = [2 => 50, 3 => 200, 4 => 800, 5 => 3000, 6 => 10000, 7 => 30000, 8 => 100000, 9 => 300000, 10 => 1000000];
                        $statsVal = [2 => 50, 3 => 250, 4 => 800, 5 => 2500, 6 => 8000, 7 => 25000, 8 => 80000, 9 => 250000, 10 => 800000];

                        CareerLevelParam::create([
                            'career_level_id' => $levelObj->id,
                            'game_param_id' => $reqRespect->id,
                            'value' => $respVal[$lvl],
                        ]);
                        CareerLevelParam::create([
                            'career_level_id' => $levelObj->id,
                            'game_param_id' => $reqStatsTotal->id,
                            'value' => $statsVal[$lvl],
                        ]);
                        break;
                }
            }
        }
    }
}
