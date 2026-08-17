<?php

namespace App\Services\GameProgress\Evaluators;

use App\Models\User;
use App\Services\GameProgress\RequirementEvaluatorInterface;

class PowerRequirementEvaluator implements RequirementEvaluatorInterface
{
    public function evaluate(User $user, object $req): int
    {
        return max($user->single_robbery_power, $user->gang_robbery_power, $user->assault_power);
    }
}
