<?php

namespace Database\Factories;

use App\Enums\ReqType;
use App\Enums\RwdType;
use App\Models\Component;
use App\Models\Drug;
use App\Models\Hooker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameParam>
 */
class GameParamFactory extends Factory
{
    public function definition(): array
    {
        $types = ['requirement', 'reward'];
        $type = $this->faker->randomElement($types);

        $relatedId = null;
        $relatedType = null;

        if ($type === 'requirement') {
            $name = $this->faker->randomElement(array_map(fn($case) => $case->value, ReqType::cases()));
        } else { // reward
            $name = $this->faker->randomElement(array_map(fn($case) => $case->value, RwdType::cases()));

            // só cria morph se for um tipo de reward que precisa
            $morphModels = [Component::class, Drug::class, Hooker::class];
            $relatedModel = $this->faker->randomElement($morphModels);

            $related = $relatedModel::inRandomOrder()->first();
            if (!$related) {
                $related = $relatedModel::factory()->create();
            }

            $relatedId = $related->id;
            $relatedType = $relatedModel;
        }

        return [
            'name' => $name,
            'type' => $type,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
        ];
    }
}
