<?php

namespace Database\Factories;

use App\Models\Drug;
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
            'name' => $this->faker->company,
            'price' => $this->faker->numberBetween(1000, 100000),
            'production' => $this->faker->numberBetween(1, 100),
            'maintenance' => $this->faker->numberBetween(100, 5000),
            'avatar' => $this->faker->imageUrl(200, 200, 'business'),
            'drug_id' => Drug::inRandomOrder()->value('id') ?? Drug::factory()->create()->id,
        ];
    }
}
