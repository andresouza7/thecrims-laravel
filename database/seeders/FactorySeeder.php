<?php

namespace Database\Seeders;

use App\Models\Drug;
use App\Models\Factory;
use App\Models\Component;
use Illuminate\Database\Seeder;

class FactorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Drugs Lab (Lab Production Factory)
        Factory::firstOrCreate([
            'name' => 'Laboratório de Drogas',
            'is_lab' => true,
        ], [
            'price' => 150000,
            'production' => 54,
            'maintenance' => 3000,
            'drug_id' => null,
            'avatar' => 'https://placehold.co/200x200/png?text=Laboratorio',
        ]);

        // 2. Factory catalog for each of the 10 drugs
        $factories = [
            'Maconha' => [
                'name' => 'Estufa de Maconha',
                'price' => 50000,
                'maintenance' => 1000,
                'production' => 50,
                'avatar' => 'https://placehold.co/200x200/png?text=Estufa',
            ],
            'Cerveja' => [
                'name' => 'Cervejaria',
                'price' => 100000,
                'maintenance' => 2005,
                'production' => 45,
                'avatar' => 'https://placehold.co/200x200/png?text=Cervejaria',
            ],
            'Anfetamina' => [
                'name' => 'Laboratório de Anfetamina',
                'price' => 150000,
                'maintenance' => 3000,
                'production' => 40,
                'avatar' => 'https://placehold.co/200x200/png?text=Anfetamina',
            ],
            'Ecstasy' => [
                'name' => 'Laboratório de Ecstasy',
                'price' => 200000,
                'maintenance' => 4000,
                'production' => 35,
                'avatar' => 'https://placehold.co/200x200/png?text=Ecstasy',
            ],
            'Metanfetamina' => [
                'name' => 'Laboratório de Metanfetamina',
                'price' => 300000,
                'maintenance' => 6000,
                'production' => 30,
                'avatar' => 'https://placehold.co/200x200/png?text=Metanfetamina',
            ],
            'LSD' => [
                'name' => 'Laboratório de LSD',
                'price' => 400000,
                'maintenance' => 8000,
                'production' => 25,
                'avatar' => 'https://placehold.co/200x200/png?text=LSD',
            ],
            'Cocaína' => [
                'name' => 'Refinaria de Cocaína',
                'price' => 500000,
                'maintenance' => 10000,
                'production' => 20,
                'avatar' => 'https://placehold.co/200x200/png?text=Refinaria',
            ],
            'Heroína' => [
                'name' => 'Fábrica de Heroína',
                'price' => 600000,
                'maintenance' => 12000,
                'production' => 15,
                'avatar' => 'https://placehold.co/200x200/png?text=Heroina',
            ],
            'Ópio' => [
                'name' => 'Fábrica de Ópio',
                'price' => 800000,
                'maintenance' => 16000,
                'production' => 10,
                'avatar' => 'https://placehold.co/200x200/png?text=Opio',
            ],
            'Special K' => [
                'name' => 'Laboratório de Special K',
                'price' => 1000000,
                'maintenance' => 20000,
                'production' => 5,
                'avatar' => 'https://placehold.co/200x200/png?text=SpecialK',
            ],
        ];

        foreach ($factories as $drugName => $data) {
            $drug = Drug::where('name', $drugName)->first();
            if (!$drug) continue;

            // Ensure Component exists first
            Component::firstOrCreate([
                'name' => 'Componente de ' . $drugName,
            ], [
                'drug_id' => $drug->id,
            ]);

            // Create Factory
            Factory::firstOrCreate([
                'name' => $data['name'],
                'drug_id' => $drug->id,
            ], [
                'price' => $data['price'],
                'maintenance' => $data['maintenance'],
                'production' => $data['production'],
                'is_lab' => false,
                'avatar' => $data['avatar'],
            ]);
        }
    }
}
