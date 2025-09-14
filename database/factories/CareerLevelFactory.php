<?php

namespace Database\Factories;

use App\Models\Career;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CareerLevel>
 */
class CareerLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'career_id' => Career::factory(), // fallback, mas vamos sobrescrever no seeder
            'level' => 1,
            'name' => $this->faker->jobTitle(),
        ];
    }
}
