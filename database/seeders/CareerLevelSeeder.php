<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\CareerLevel;
use Illuminate\Database\Seeder;

class CareerLevelSeeder extends Seeder
{
    public function run(): void
    {
        $careers = [
            'Empresário',
            'Traficante',
            'Cafetão',
            'Assassino',
            'Ladrão',
            'Vigarista',
        ];

        // Level title templates to give RPG flavor to levels 1-10
        $levelNames = [
            1 => 'Recruta',
            2 => 'Iniciado',
            3 => 'Capanga',
            4 => 'Soldado',
            5 => 'Tenente',
            6 => 'Capitão',
            7 => 'Subchefe',
            8 => 'Conselheiro',
            9 => 'Chefão',
            10 => 'Padrinho',
        ];

        foreach ($careers as $name) {
            $career = Career::firstOrCreate(['name' => $name]);

            foreach (range(1, 10) as $lvlNum) {
                CareerLevel::firstOrCreate([
                    'career_id' => $career->id,
                    'level' => $lvlNum,
                ], [
                    'name' => $levelNames[$lvlNum],
                ]);
            }
        }
    }
}
