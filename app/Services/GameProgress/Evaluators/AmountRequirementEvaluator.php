<?php

namespace App\Services\GameProgress\Evaluators;

use App\Enums\ReqType;
use App\Models\User;
use App\Services\GameProgress\RequirementEvaluatorInterface;

class AmountRequirementEvaluator implements RequirementEvaluatorInterface
{
    public function evaluate(User $user, object $req): int
    {
        return $user->{$req->value} ?? 0;
    }
}
