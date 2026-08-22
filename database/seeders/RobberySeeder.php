<?php

namespace Database\Seeders;

use App\Models\Robbery;
use App\Models\Drug;
use App\Models\Component;
use Illuminate\Database\Seeder;

class RobberySeeder extends Seeder
{
    public function run(): void
    {


        $maconha = Drug::where('name', 'Maconha')->first();
        $meta = Drug::where('name', 'Metanfetamina')->first();
        $cocaina = Drug::where('name', 'Cocaína')->first();
        $heroina = Drug::where('name', 'Heroína')->first();

        $compMaconha = Component::where('drug_id', $maconha?->id)->first();
        $compMeta = Component::where('drug_id', $meta?->id)->first();
        $compCocaina = Component::where('drug_id', $cocaina?->id)->first();

        $robberies = [
            [
                'description' => 'Mendigar',
                'required_power' => 3,
                'required_stamina' => 10,
                'type' => 'solo',
                'cash' => 50,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Bater Carteira',
                'required_power' => 10,
                'required_stamina' => 10,
                'type' => 'solo',
                'cash' => 200,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Roubar Padaria',
                'required_power' => 40,
                'required_stamina' => 15,
                'type' => 'solo',
                'cash' => 600,
                'drugs' => $maconha ? [['drug_id' => $maconha->id, 'amount' => 2]] : [],
                'components' => $compMaconha ? [['component_id' => $compMaconha->id, 'amount' => 1]] : [],
            ],
            [
                'description' => 'Assaltar Posto',
                'required_power' => 150,
                'required_stamina' => 20,
                'type' => 'solo',
                'cash' => 1500,
                'drugs' => $maconha ? [['drug_id' => $maconha->id, 'amount' => 5]] : [],
                'components' => [],
            ],
            [
                'description' => 'Roubar Carro',
                'required_power' => 500,
                'required_stamina' => 25,
                'type' => 'solo',
                'cash' => 4500,
                'drugs' => $meta ? [['drug_id' => $meta->id, 'amount' => 3]] : [],
                'components' => $compMeta ? [['component_id' => $compMeta->id, 'amount' => 2]] : [],
            ],
            [
                'description' => 'Invadir Mansão',
                'required_power' => 1500,
                'required_stamina' => 30,
                'type' => 'solo',
                'cash' => 12000,
                'drugs' => $cocaina ? [['drug_id' => $cocaina->id, 'amount' => 5]] : [],
                'components' => [],
            ],
            [
                'description' => 'Assaltar Joalheria',
                'required_power' => 4500,
                'required_stamina' => 35,
                'type' => 'solo',
                'cash' => 35000,
                'drugs' => $cocaina ? [['drug_id' => $cocaina->id, 'amount' => 10]] : [],
                'components' => $compCocaina ? [['component_id' => $compCocaina->id, 'amount' => 3]] : [],
            ],
            [
                'description' => 'Seqüestrar Político',
                'required_power' => 12000,
                'required_stamina' => 40,
                'type' => 'solo',
                'cash' => 100000,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Assaltar Banco',
                'required_power' => 30000,
                'required_stamina' => 45,
                'type' => 'solo',
                'cash' => 300000,
                'drugs' => $heroina ? [['drug_id' => $heroina->id, 'amount' => 8]] : [],
                'components' => [],
            ],
            [
                'description' => 'Roubar Cassino',
                'required_power' => 80000,
                'required_stamina' => 50,
                'type' => 'solo',
                'cash' => 800000,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Invadir Base Militar',
                'required_power' => 200000,
                'required_stamina' => 60,
                'type' => 'solo',
                'cash' => 2500000,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Assaltar Reserva Federal',
                'required_power' => 500000,
                'required_stamina' => 70,
                'type' => 'solo',
                'cash' => 8000000,
                'drugs' => [],
                'components' => [],
            ],
        ];

        foreach ($robberies as $robbery) {
            Robbery::create($robbery);
        }
    }
}
