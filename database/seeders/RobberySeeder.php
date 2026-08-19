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
        $compHeroina = Component::where('drug_id', $heroina?->id)->first();

        $allDrugs = function ($amount) use ($maconha, $meta, $cocaina, $heroina) {
            return ($maconha && $meta && $cocaina && $heroina) ? [
                ['drug_id' => $maconha->id, 'amount' => $amount],
                ['drug_id' => $meta->id, 'amount' => $amount],
                ['drug_id' => $cocaina->id, 'amount' => $amount],
                ['drug_id' => $heroina->id, 'amount' => $amount],
            ] : [];
        };

        $allComponents = function ($amount) use ($compMaconha, $compMeta, $compCocaina, $compHeroina) {
            return ($compMaconha && $compMeta && $compCocaina && $compHeroina) ? [
                ['component_id' => $compMaconha->id, 'amount' => $amount],
                ['component_id' => $compMeta->id, 'amount' => $amount],
                ['component_id' => $compCocaina->id, 'amount' => $amount],
                ['component_id' => $compHeroina->id, 'amount' => $amount],
            ] : [];
        };

        $robberies = [
            [
                'description' => 'Pedir esmola fingindo ser mudo',
                'required_power' => 3,
                'required_stamina' => 10,
                'type' => 'solo',
                'cash' => 50,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Bater carteira de idosa na praça',
                'required_power' => 10,
                'required_stamina' => 10,
                'type' => 'solo',
                'cash' => 150,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Roubar doce de criança',
                'required_power' => 20,
                'required_stamina' => 10,
                'type' => 'solo',
                'cash' => 300,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Invadir horta e depósitos comunitários',
                'required_power' => 45,
                'required_stamina' => 15,
                'type' => 'solo',
                'cash' => 0,
                'drugs' => $allDrugs(15),
                'components' => [],
            ],
            [
                'description' => 'Assaltar vendedor de cachorro-quente',
                'required_power' => 70,
                'required_stamina' => 15,
                'type' => 'solo',
                'cash' => 800,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Roubar bicicletas de corrida na ciclovia',
                'required_power' => 110,
                'required_stamina' => 20,
                'type' => 'solo',
                'cash' => 1500,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Desviar insumos e fertilizantes do porto',
                'required_power' => 160,
                'required_stamina' => 20,
                'type' => 'solo',
                'cash' => 0,
                'drugs' => [],
                'components' => $allComponents(25),
            ],
            [
                'description' => 'Arrombar caixa eletrônico de posto de gasolina',
                'required_power' => 260,
                'required_stamina' => 25,
                'type' => 'solo',
                'cash' => 4500,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Saquear farmácia de manipulação no centro',
                'required_power' => 420,
                'required_stamina' => 25,
                'type' => 'solo',
                'cash' => 0,
                'drugs' => $allDrugs(10),
                'components' => [],
            ],
            [
                'description' => 'Assaltar mercadinho de bairro no fim do expediente',
                'required_power' => 650,
                'required_stamina' => 30,
                'type' => 'solo',
                'cash' => 9000,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Contrabandear precursores químicos importados',
                'required_power' => 950,
                'required_stamina' => 30,
                'type' => 'solo',
                'cash' => 0,
                'drugs' => [],
                'components' => $allComponents(15),
            ],
            [
                'description' => 'Interceptar e roubar carreta de carga eletrônica',
                'required_power' => 1400,
                'required_stamina' => 35,
                'type' => 'solo',
                'cash' => 18000,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Saquear consultório de clínica médica privada',
                'required_power' => 2000,
                'required_stamina' => 35,
                'type' => 'solo',
                'cash' => 0,
                'drugs' => $allDrugs(8),
                'components' => [],
            ],
            [
                'description' => 'Assaltar joalheria de shopping na calada da noite',
                'required_power' => 2800,
                'required_stamina' => 40,
                'type' => 'solo',
                'cash' => 35000,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Desviar reagentes de laboratório de pesquisa federal',
                'required_power' => 3800,
                'required_stamina' => 40,
                'type' => 'solo',
                'cash' => 0,
                'drugs' => [],
                'components' => $allComponents(12),
            ],
            [
                'description' => 'Realizar sequestro relâmpago de grande empresário',
                'required_power' => 5500,
                'required_stamina' => 45,
                'type' => 'solo',
                'cash' => 70000,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Roubar museu de arte histórica da capital',
                'required_power' => 8000,
                'required_stamina' => 45,
                'type' => 'solo',
                'cash' => 0,
                'drugs' => $allDrugs(6),
                'components' => [],
            ],
            [
                'description' => 'Planejar e assaltar carro forte em plena rodovia',
                'required_power' => 12000,
                'required_stamina' => 50,
                'type' => 'solo',
                'cash' => 150000,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Infiltrar e saquear depósito químico militar',
                'required_power' => 18000,
                'required_stamina' => 50,
                'type' => 'solo',
                'cash' => 0,
                'drugs' => [],
                'components' => $allComponents(8),
            ],
            [
                'description' => 'Assaltar a sede principal do Banco Central',
                'required_power' => 28000,
                'required_stamina' => 60,
                'type' => 'solo',
                'cash' => 500000,
                'drugs' => [],
                'components' => [],
            ],
            [
                'description' => 'Saquear o cofre de alta segurança da Reserva Federal',
                'required_power' => 55000,
                'required_stamina' => 70,
                'type' => 'solo',
                'cash' => 1500000,
                'drugs' => [],
                'components' => [],
            ],
        ];

        foreach ($robberies as $r) {
            Robbery::create($r);
        }
    }
}
