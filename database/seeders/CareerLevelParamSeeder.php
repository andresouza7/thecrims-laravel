<?php

namespace Database\Seeders;

use App\Models\CareerLevel;
use App\Models\CareerLevelParam;
use App\Models\GameParam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CareerLevelParamSeeder extends Seeder
{
    public function run(): void
    {
        // Pega todos os career levels existentes
        CareerLevel::all()->each(function (CareerLevel $level) {
            // Pega alguns params aleatórios para esse level
            $requirements = GameParam::where('type', 'requirement')->inRandomOrder()->take(3)->get();
            $rewards = GameParam::where('type', 'reward')->inRandomOrder()->take(2)->get();

            // Combina requirements e rewards
            $params = $requirements->merge($rewards);

            foreach ($params as $param) {
                // Garante que não repete para o mesmo level
                CareerLevelParam::firstOrCreate([
                    'career_level_id' => $level->id,
                    'game_param_id' => $param->id,
                    'value' => fake()->numberBetween(1, 1000)
                ]);
            }
        });
    }
}
