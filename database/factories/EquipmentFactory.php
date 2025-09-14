<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Equipment;

class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    public function definition(): array
    {
        $types = ['gang', 'solo', 'assault', 'armor'];

        return [
            'name' => $this->faker->word,
            'type' => $this->faker->randomElement($types),
            'avatar' => $this->faker->imageUrl(200, 200, 'technics', true), // placeholder avatar
            'price' => $this->faker->numberBetween(1000, 100000),
            'required_level' => 1,
            'multiplier' => $this->faker->randomFloat(2, 1, 3), // e.g., 1.00 - 3.00
            'base_damage' => $this->faker->numberBetween(5, 50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
