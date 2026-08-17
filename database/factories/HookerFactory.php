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
        $price = $this->faker->numberBetween(500, 100000);
        // Daily income is proportional to price (e.g., 15% daily return)
        $income = (int) round($price * 0.15);

        return [
            'name' => $this->faker->name(),
            'price' => $price,
            'income' => $income,
            'avatar' => $this->faker->url(),
        ];
    }
}
