<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Stackable
{
    public function users(): BelongsToMany;
    public function getAmountForUser(User $user): int;
}