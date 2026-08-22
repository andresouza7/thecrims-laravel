<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Weapons (10 types, type can be solo/assault/gang)
            [
                'name' => 'Soco Inglês',
                'type' => 'solo',
                'avatar' => 'https://placehold.co/200x200/png?text=SocoIngles',
                'price' => 100,
                'required_level' => 1,
                'multiplier' => 1.05,
                'base_damage' => 5,
            ],
            [
                'name' => 'Taco de Beisebol',
                'type' => 'solo',
                'avatar' => 'https://placehold.co/200x200/png?text=TacoBeisebol',
                'price' => 400,
                'required_level' => 1,
                'multiplier' => 1.10,
                'base_damage' => 15,
            ],
            [
                'name' => 'Faca de Combate',
                'type' => 'solo',
                'avatar' => 'https://placehold.co/200x200/png?text=FacaCombate',
                'price' => 1500,
                'required_level' => 1,
                'multiplier' => 1.15,
                'base_damage' => 35,
            ],
            [
                'name' => 'Pistola .38',
                'type' => 'assault',
                'avatar' => 'https://placehold.co/200x200/png?text=Pistola38',
                'price' => 5000,
                'required_level' => 1,
                'multiplier' => 1.20,
                'base_damage' => 80,
            ],
            [
                'name' => 'Pistola 9mm Glock',
                'type' => 'assault',
                'avatar' => 'https://placehold.co/200x200/png?text=Glock',
                'price' => 15000,
                'required_level' => 1,
                'multiplier' => 1.25,
                'base_damage' => 180,
            ],
            [
                'name' => 'Escopeta Calibre 12',
                'type' => 'assault',
                'avatar' => 'https://placehold.co/200x200/png?text=Escopeta12',
                'price' => 40000,
                'required_level' => 1,
                'multiplier' => 1.30,
                'base_damage' => 400,
            ],
            [
                'name' => 'Submetralhadora Uzi',
                'type' => 'gang',
                'avatar' => 'https://placehold.co/200x200/png?text=Uzi',
                'price' => 100000,
                'required_level' => 1,
                'multiplier' => 1.40,
                'base_damage' => 900,
            ],
            [
                'name' => 'Fuzil de Assalto M4A1',
                'type' => 'gang',
                'avatar' => 'https://placehold.co/200x200/png?text=M4A1',
                'price' => 200000,
                'required_level' => 1,
                'multiplier' => 1.50,
                'base_damage' => 2000,
            ],
            [
                'name' => 'Fuzil Sniper .50',
                'type' => 'gang',
                'avatar' => 'https://placehold.co/200x200/png?text=Sniper50',
                'price' => 350000,
                'required_level' => 1,
                'multiplier' => 1.70,
                'base_damage' => 5000,
            ],
            [
                'name' => 'Lança-Mísseis RPG',
                'type' => 'gang',
                'avatar' => 'https://placehold.co/200x200/png?text=RPG',
                'price' => 500000,
                'required_level' => 1,
                'multiplier' => 2.00,
                'base_damage' => 12000,
            ],

            // Armors (10 types, type must be 'armor')
            [
                'name' => 'Jaqueta de Couro',
                'type' => 'armor',
                'avatar' => 'https://placehold.co/200x200/png?text=JaquetaCouro',
                'price' => 50,
                'required_level' => 1,
                'multiplier' => 1.03,
                'base_damage' => 3,
            ],
            [
                'name' => 'Jaqueta de Couro Reforçada',
                'type' => 'armor',
                'avatar' => 'https://placehold.co/200x200/png?text=JaquetaReforcada',
                'price' => 200,
                'required_level' => 1,
                'multiplier' => 1.06,
                'base_damage' => 10,
            ],
            [
                'name' => 'Colete Tático Simples',
                'type' => 'armor',
                'avatar' => 'https://placehold.co/200x200/png?text=ColeteTatico',
                'price' => 1000,
                'required_level' => 1,
                'multiplier' => 1.10,
                'base_damage' => 25,
            ],
            [
                'name' => 'Colete Kevlar Leve',
                'type' => 'armor',
                'avatar' => 'https://placehold.co/200x200/png?text=KevlarLeve',
                'price' => 4000,
                'required_level' => 1,
                'multiplier' => 1.15,
                'base_damage' => 60,
            ],
            [
                'name' => 'Colete Kevlar Reforçado',
                'type' => 'armor',
                'avatar' => 'https://placehold.co/200x200/png?text=KevlarReforcado',
                'price' => 12000,
                'required_level' => 1,
                'multiplier' => 1.20,
                'base_damage' => 130,
            ],
            [
                'name' => 'Traje Tático SWAT',
                'type' => 'armor',
                'avatar' => 'https://placehold.co/200x200/png?text=SWAT',
                'price' => 30000,
                'required_level' => 1,
                'multiplier' => 1.25,
                'base_damage' => 300,
            ],
            [
                'name' => 'Colete de Placas de Cerâmica',
                'type' => 'armor',
                'avatar' => 'https://placehold.co/200x200/png?text=PlacasCeramica',
                'price' => 80000,
                'required_level' => 1,
                'multiplier' => 1.35,
                'base_damage' => 700,
            ],
            [
                'name' => 'Armadura Corporal Militar',
                'type' => 'armor',
                'avatar' => 'https://placehold.co/200x200/png?text=ArmorMilitar',
                'price' => 150000,
                'required_level' => 1,
                'multiplier' => 1.45,
                'base_damage' => 1600,
            ],
            [
                'name' => 'Armadura Exoesquelética',
                'type' => 'armor',
                'avatar' => 'https://placehold.co/200x200/png?text=Exoesqueleto',
                'price' => 250000,
                'required_level' => 1,
                'multiplier' => 1.60,
                'base_damage' => 4000,
            ],
            [
                'name' => 'Traje Nano-Protetor',
                'type' => 'armor',
                'avatar' => 'https://placehold.co/200x200/png?text=NanoProtetor',
                'price' => 400000,
                'required_level' => 1,
                'multiplier' => 1.90,
                'base_damage' => 10000,
            ],
        ];

        foreach ($items as $item) {
            Equipment::firstOrCreate([
                'name' => $item['name']
            ], [
                'type' => $item['type'],
                'avatar' => $item['avatar'],
                'price' => $item['price'],
                'required_level' => $item['required_level'],
                'multiplier' => $item['multiplier'],
                'base_damage' => $item['base_damage'],
            ]);
        }
    }
}
