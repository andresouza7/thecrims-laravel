<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\Component;
use App\Models\Drug;
use App\Models\Equipment;
use App\Models\Factory;
use App\Models\Hooker;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(3)->create();
        Career::factory(5)->create();

        // 1. Seed drugs with prices on a 0-100 scale, with very low and very high values.
        // 2. Ensure each component produces exactly one distinct type of drug.
        $drugData = [
            ['name' => 'Maconha', 'price' => 8],       // Baixíssimo
            ['name' => 'Metanfetamina', 'price' => 35], // Médio-baixo
            ['name' => 'Cocaína', 'price' => 75],      // Médio-alto
            ['name' => 'Heroína', 'price' => 98],      // Altíssimo
        ];

        foreach ($drugData as $data) {
            $drug = Drug::create([
                'name' => $data['name'],
                'price' => $data['price'],
            ]);

            Component::create([
                'name' => 'Componente de ' . $data['name'],
                'drug_id' => $drug->id,
            ]);
        }

        // 3. Seed Laboratory and distinct factories with differing prices and productions.
        Factory::create([
            'name' => 'Laboratório de Drogas',
            'price' => 150000,
            'production' => 54, // Base capacity limit factor
            'maintenance' => 2500,
            'is_lab' => true,
            'drug_id' => null,
            'avatar' => 'https://placehold.co/200x200/png?text=Laboratorio',
        ]);

        $maconha = Drug::where('name', 'Maconha')->first();
        $meta = Drug::where('name', 'Metanfetamina')->first();
        $cocaina = Drug::where('name', 'Cocaína')->first();
        $heroina = Drug::where('name', 'Heroína')->first();

        if ($maconha) {
            Factory::create([
                'name' => 'Estufa de Maconha',
                'price' => 25000,
                'production' => 120,
                'maintenance' => 450,
                'is_lab' => false,
                'drug_id' => $maconha->id,
                'avatar' => 'https://placehold.co/200x200/png?text=Estufa',
            ]);
        }

        if ($meta) {
            Factory::create([
                'name' => 'Laboratório de Metanfetamina',
                'price' => 75000,
                'production' => 70,
                'maintenance' => 1200,
                'is_lab' => false,
                'drug_id' => $meta->id,
                'avatar' => 'https://placehold.co/200x200/png?text=Metanfetamina',
            ]);
        }

        if ($cocaina) {
            Factory::create([
                'name' => 'Refinaria de Cocaína',
                'price' => 180000,
                'production' => 40,
                'maintenance' => 3200,
                'is_lab' => false,
                'drug_id' => $cocaina->id,
                'avatar' => 'https://placehold.co/200x200/png?text=Refinaria',
            ]);
        }

        if ($heroina) {
            Factory::create([
                'name' => 'Fábrica de Heroína',
                'price' => 380000,
                'production' => 20,
                'maintenance' => 6500,
                'is_lab' => false,
                'drug_id' => $heroina->id,
                'avatar' => 'https://placehold.co/200x200/png?text=Heroina',
            ]);
        }

        Hooker::factory(5)->create();
        Equipment::factory(5)->create();

        $this->call([
            CareerLevelSeeder::class,
            GameParamSeeder::class,
            CareerLevelParamSeeder::class,
        ]);
    }
}
