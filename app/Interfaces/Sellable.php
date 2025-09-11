<?php

namespace App\Interfaces;

use App\Models\User;

interface Sellable
{
    public function getPrice(): int;
    public function getName(): string;

    public function removeFromUser(User $user, int $quantity = 1): void;
    public function validateInventory(User $user, int $quantity = 1): void;
}