<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\CareerLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CareerLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Para cada carreira existente
        Career::all()->each(function (Career $career) {
            // Cria 5 níveis sequenciais
            foreach (range(1, 5) as $level) {
                CareerLevel::factory()->create([
                    'career_id' => $career->id,
                    'level' => $level,
                    'name' => $career->name . " - Level {$level}",
                ]);
            }
        });
    }
}
