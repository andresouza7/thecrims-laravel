<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Handles buying and selling of items for a user.
 */
class MarketService
{
    public function __construct(
        protected User $user
    ) {}

    public function buy(Buyable $item, int $quantity = 1): mixed
    {
        return DB::transaction(function () use ($item, $quantity) {
            $cost = $this->calculateTotal($item, $quantity);

            $this->validateFunds($cost);
            $this->adjustCash(-$cost);

            $item->addToUser($this->user, $quantity);

            return $item;
        });
    }

    public function sell(Sellable $item, int $quantity = 1): mixed
    {
        return DB::transaction(function () use ($item, $quantity) {
            $this->validateInventory($item, $quantity);

            $profit = $this->calculateTotal($item, $quantity);

            $this->adjustCash($profit);
            $item->removeFromUser($this->user, $quantity);

            $this->user->increment('drug_profits', $profit);

            return $profit;
        });
    }

    protected function calculateTotal(Item $item, int $quantity): int
    {
        return $item->getPrice() * $quantity;
    }

    protected function validateFunds(int $cost): void
    {
        if ($this->user->cash < $cost) {
            throw new \RuntimeException("Not enough cash.");
        }
    }

    protected function validateInventory(Sellable $item, int $quantity): void
    {
        if ($item instanceof StackableItem) {
            $stash = $item->getAmountForUser($this->user);
            if (!$stash || $stash < $quantity) {
                throw new \RuntimeException("Not enough of {$item->name} to sell.");
            }
        }
    }

    protected function adjustCash(int $amount): void
    {
        $this->user->increment('cash', $amount);
    }
}
