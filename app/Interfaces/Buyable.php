<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Buyable
{
    public function getPrice(): int;
    public function getName(): string;
    public function users(): BelongsToMany;

    public function addToUser(User $user, int $quantity = 1): void;
}