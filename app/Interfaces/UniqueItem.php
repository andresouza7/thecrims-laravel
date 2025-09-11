<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface UniqueItem
{
    /**
     * Get the price of the item
     */
    public function getPrice(): int;

    /**
     * Remove a quantity of this item from a user
     */
    public function removeFromUser(): void;
}
