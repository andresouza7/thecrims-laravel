<?php

namespace Database\Seeders;

use App\Models\Drug;
use Illuminate\Database\Seeder;

class DrugSeeder extends Seeder
{
    public function run(): void
    {
        $drugs = [
            ['name' => 'Maconha', 'price' => 10],
            ['name' => 'Cerveja', 'price' => 20],
            ['name' => 'Anfetamina', 'price' => 30],
            ['name' => 'Ecstasy', 'price' => 40],
            ['name' => 'Metanfetamina', 'price' => 50],
            ['name' => 'LSD', 'price' => 60],
            ['name' => 'Cocaína', 'price' => 70],
            ['name' => 'Heroína', 'price' => 80],
            ['name' => 'Ópio', 'price' => 90],
            ['name' => 'Special K', 'price' => 100],
        ];

        foreach ($drugs as $drug) {
            Drug::firstOrCreate(['name' => $drug['name']], ['price' => $drug['price']]);
        }
    }
}
