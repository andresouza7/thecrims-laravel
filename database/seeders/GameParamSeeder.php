<?php

namespace Database\Seeders;

use App\Models\Drug;
use App\Models\Equipment;
use App\Models\GameParam;
use App\Models\Hooker;
use Illuminate\Database\Seeder;

class GameParamSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Generic Requirements
        GameParam::firstOrCreate(['name' => 'cash', 'type' => 'requirement', 'target_type' => null, 'target_id' => null]);
        GameParam::firstOrCreate(['name' => 'respect', 'type' => 'requirement', 'target_type' => null, 'target_id' => null]);
        GameParam::firstOrCreate(['name' => 'hookers_count', 'type' => 'requirement', 'target_type' => null, 'target_id' => null]);
        GameParam::firstOrCreate(['name' => 'stats_total', 'type' => 'requirement', 'target_type' => null, 'target_id' => null]);

        // 2. Generic Rewards
        GameParam::firstOrCreate(['name' => 'cash', 'type' => 'reward', 'target_type' => null, 'target_id' => null]);
        GameParam::firstOrCreate(['name' => 'respect', 'type' => 'reward', 'target_type' => null, 'target_id' => null]);
        GameParam::firstOrCreate(['name' => 'stamina', 'type' => 'reward', 'target_type' => null, 'target_id' => null]);

        // 3. Specific Drug Params (Requirement & Reward)
        Drug::all()->each(function (Drug $drug) {
            GameParam::firstOrCreate([
                'name' => 'drug_sold',
                'type' => 'requirement',
                'target_type' => Drug::class,
                'target_id' => $drug->id,
            ]);

            GameParam::firstOrCreate([
                'name' => 'drug_received',
                'type' => 'reward',
                'target_type' => Drug::class,
                'target_id' => $drug->id,
            ]);
        });

        // 4. Specific Equipment Params
        Equipment::all()->each(function (Equipment $equipment) {
            GameParam::firstOrCreate([
                'name' => 'equipment_owned',
                'type' => 'requirement',
                'target_type' => Equipment::class,
                'target_id' => $equipment->id,
            ]);

            GameParam::firstOrCreate([
                'name' => 'equipment_received',
                'type' => 'reward',
                'target_type' => Equipment::class,
                'target_id' => $equipment->id,
            ]);
        });

        // 5. Specific Hooker Params
        Hooker::all()->each(function (Hooker $hooker) {
            GameParam::firstOrCreate([
                'name' => 'hooker_type_owned',
                'type' => 'requirement',
                'target_type' => Hooker::class,
                'target_id' => $hooker->id,
            ]);
        });
    }
}
