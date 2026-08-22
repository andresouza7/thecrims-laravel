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
            'cash' => 1000,
        ]);

        User::factory(3)->create([
            'cash' => 1000,
        ]);

        $this->call([
            DrugSeeder::class,
            FactorySeeder::class,
            HookerSeeder::class,
            EquipmentSeeder::class,
            RobberySeeder::class,
            CareerLevelSeeder::class,
            GameParamSeeder::class,
            CareerLevelParamSeeder::class,
            TaskSeeder::class,
        ]);

        \App\Services\GameService::createRound();
    }
}
