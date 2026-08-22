<?php

namespace Database\Seeders;

use App\Models\Hooker;
use Illuminate\Database\Seeder;

class HookerSeeder extends Seeder
{
    public function run(): void
    {
        $hookers = [
            ['name' => 'Bete do Calçadão', 'price' => 50, 'income' => 5, 'avatar' => 'https://placehold.co/200x200/png?text=Bete'],
            ['name' => 'Samanta da Esquina', 'price' => 150, 'income' => 15, 'avatar' => 'https://placehold.co/200x200/png?text=Samanta'],
            ['name' => 'Carol da Boate', 'price' => 500, 'income' => 50, 'avatar' => 'https://placehold.co/200x200/png?text=Carol'],
            ['name' => 'Jéssica do Motel', 'price' => 1500, 'income' => 150, 'avatar' => 'https://placehold.co/200x200/png?text=Jessica'],
            ['name' => 'Aline de Luxo', 'price' => 4000, 'income' => 400, 'avatar' => 'https://placehold.co/200x200/png?text=Aline'],
            ['name' => 'Mônica do Privê', 'price' => 10000, 'income' => 1000, 'avatar' => 'https://placehold.co/200x200/png?text=Monica'],
            ['name' => 'Patrícia VIP', 'price' => 20000, 'income' => 2000, 'avatar' => 'https://placehold.co/200x200/png?text=Patricia'],
            ['name' => 'Valéria Internacional', 'price' => 30000, 'income' => 3000, 'avatar' => 'https://placehold.co/200x200/png?text=Valeria'],
            ['name' => 'Catarina de Elite', 'price' => 40000, 'income' => 4000, 'avatar' => 'https://placehold.co/200x200/png?text=Catarina'],
            ['name' => 'Imperatriz da Noite', 'price' => 50000, 'income' => 5500, 'avatar' => 'https://placehold.co/200x200/png?text=Imperatriz'],
        ];

        foreach ($hookers as $hooker) {
            Hooker::firstOrCreate([
                'name' => $hooker['name']
            ], [
                'price' => $hooker['price'],
                'income' => $hooker['income'],
                'avatar' => $hooker['avatar'],
            ]);
        }
    }
}
