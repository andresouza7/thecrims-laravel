<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Factory>
 */
class FactoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Factory',
            'price' => 50000,
            'production' => 50,
            'maintenance' => 1000,
            'is_lab' => false,
            'drug_id' => null,
            'avatar' => fake()->imageUrl(),
        ];
    }
}
