<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface Buyable
{
    /**
     * Get the price of the item
     */
    public function getPrice(): int;

    /**
     * Get the amount of the item
     */
    public function getAmountForUser(Model $user): int;

    /**
     * Add a quantity of this item to a user
     */
    public function addToUser(Model $user, int $quantity): void;

    /**
     * Remove a quantity of this item from a user
     */
    public function removeFromUser(Model $user, int $quantity): void;

    /**
     * Optional: get the users relationship (pivot)
     */
    public function users();
}
