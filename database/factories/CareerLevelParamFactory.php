<?php

namespace Database\Factories;

use App\Models\CareerLevel;
use App\Models\CareerLevelParam;
use App\Models\GameParam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CareerLevelParam>
 */
class CareerLevelParamFactory extends Factory
{
    protected $model = CareerLevelParam::class;

    public function definition(): array
    {
        return [
            'career_level_id' => CareerLevel::factory(),
            'game_param_id' => GameParam::factory(),
            'value' => fake()->numberBetween(1, 1000)
        ];
    }
}
