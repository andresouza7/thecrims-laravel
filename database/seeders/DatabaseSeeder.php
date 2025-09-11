<?php

namespace Database\Seeders;

use App\Models\Component;
use App\Models\Drug;
use App\Models\Factory;
use App\Models\Hooker;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Drug::factory(3)->create();
        Component::factory(3)->create();
        Factory::factory(3)->create();
        Hooker::factory(10)->create();
    }
}
