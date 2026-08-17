<?php

namespace App\Services\GameProgress;

use App\Enums\ReqType;
use App\Services\GameProgress\Evaluators\AmountRequirementEvaluator;
use App\Services\GameProgress\Evaluators\CashRequirementEvaluator;
use App\Services\GameProgress\Evaluators\PowerRequirementEvaluator;
// Import other evaluators here...

class RequirementEvaluatorFactory
{
    /**
     * Create an evaluator instance based on requirement type.
     *
     * @param string $type
     * @return RequirementEvaluatorInterface
     */
    public static function make(string $type): RequirementEvaluatorInterface
    {
        if (in_array($type, [
            ReqType::Cash,
            ReqType::Bank,
            ReqType::Level,
            ReqType::BoatProfits,
            ReqType::FactoryProfits,
            ReqType::HookerProfits,
            ReqType::Respect,
        ], true)) {
            return new AmountRequirementEvaluator();
        }

        return match ($type) {
            'power' => new PowerRequirementEvaluator(),
            // Map other types to their evaluators here...
            // default => new DefaultRequirementEvaluator(),
        };
    }
}
