<?php

namespace Database\Factories;

use App\Models\Hooker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hooker>
 */
class HookerFactory extends Factory
{
    protected $model = Hooker::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'income' => fake()->numberBetween(10, 10000),
            'price' => fake()->numberBetween(50, 100000),
            'avatar' => fake()->url(),
        ];
    }
}
