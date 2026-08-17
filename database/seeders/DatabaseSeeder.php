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
        Drug::factory(5)->create();
        Component::factory(5)->create();
        Factory::factory(5)->create();
        Hooker::factory(5)->create();
        Equipment::factory(5)->create();

        $this->call([
            CareerLevelSeeder::class,
            GameParamSeeder::class,
            CareerLevelParamSeeder::class,
        ]);
    }
}
