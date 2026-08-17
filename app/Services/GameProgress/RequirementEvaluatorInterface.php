<?php
namespace App\Services\GameProgress;

use App\Models\User;

interface RequirementEvaluatorInterface
{
    /**
     * Evaluate the progress of a requirement for a given user.
     *
     * @param User $user
     * @param object $req
     * @return int
     */
    public function evaluate(User $user, object $req): int;
}
