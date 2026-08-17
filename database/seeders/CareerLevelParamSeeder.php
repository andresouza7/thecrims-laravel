<?php

namespace Database\Seeders;

use App\Models\CareerLevel;
use App\Models\CareerLevelParam;
use App\Models\GameParam;
use Illuminate\Database\Seeder;

class CareerLevelParamSeeder extends Seeder
{
    public function run(): void
    {
        CareerLevel::all()->each(function (CareerLevel $level) {
            // Level 1 HAS NO REQUIREMENTS AND NO REWARDS
            if ($level->level <= 1) {
                return;
            }

            $multiplier = match ((int) $level->level) {
                2 => 1,
                3 => 10,
                4 => 100,
                5 => 1000,
                default => pow(10, max(0, $level->level - 2)),
            };

            // Select 2-3 requirements and 1-2 rewards
            $requirements = GameParam::requirements()->inRandomOrder()->take(rand(2, 3))->get();
            $rewards = GameParam::rewards()->inRandomOrder()->take(rand(1, 2))->get();

            foreach ($requirements as $param) {
                $baseValue = match ($param->name) {
                    'cash' => 1000,
                    'respect' => 100,
                    'hookers_count' => 5,
                    'stats_total' => 50,
                    'drug_sold' => 50,
                    'equipment_owned' => 1,
                    'hooker_type_owned' => 2,
                    default => 10,
                };

                // equipment_owned is a boolean check (has equipment or not), so value is always 1
                $finalValue = ($param->name === 'equipment_owned') ? 1 : ($baseValue * $multiplier);

                CareerLevelParam::firstOrCreate([
                    'career_level_id' => $level->id,
                    'game_param_id' => $param->id,
                ], [
                    'value' => $finalValue,
                ]);
            }

            foreach ($rewards as $param) {
                $baseValue = match ($param->name) {
                    'cash' => 500,
                    'respect' => 50,
                    'stamina' => 50,
                    'drug_received' => 25,
                    'equipment_received' => 1,
                    default => 5,
                };

                // equipment_received is a single item reward, so value is always 1
                $finalValue = ($param->name === 'equipment_received') ? 1 : ($baseValue * $multiplier);

                CareerLevelParam::firstOrCreate([
                    'career_level_id' => $level->id,
                    'game_param_id' => $param->id,
                ], [
                    'value' => $finalValue,
                ]);
            }
        });
    }
}
