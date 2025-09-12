<?php

namespace App\Services;

use App\Interfaces\Buyable;
use App\Interfaces\Sellable;
use App\Models\User;
use App\Models\UserFactory;
use Illuminate\Support\Facades\DB;

/**
 * Handles buying and selling of items for a user.
 */
class MarketService
{
    // public function __construct(protected User $user) {}
    public User $user;

    public function __construct()
    {
        $user = User::first();
        $this->user = $user;
    }

    public function buy(Buyable $item, int $quantity = 1): mixed
    {
        return DB::transaction(function () use ($item, $quantity) {
            $cost = $item->getPrice() * $quantity;

            $this->validateFunds($cost);
            $this->adjustCash(-$cost);

            $item->addToUser($this->user, $quantity);

            return $item;
        });
    }

    public function sell(Sellable $item, int $quantity = 1): mixed
    {
        return DB::transaction(function () use ($item, $quantity) {
            $item->validateInventory($this->user, $quantity);

            $profit = $item->getPrice() * $quantity;

            $this->adjustCash($profit);
            $item->removeFromUser($this->user, $quantity);

            $this->user->increment('drug_profits', $profit);

            return $profit;
        });
    }

    protected function validateFunds(int $cost): void
    {
        if ($this->user->cash < $cost) {
            throw new \RuntimeException("Not enough cash.");
        }
    }

    protected function adjustCash(int $amount): void
    {
        $this->user->increment('cash', $amount);
    }

    public function rewardItem(Buyable $item, int $quantity): void
    {
        $item->addToUser($this->user, $quantity);
    }

    public function upgradeFactory(UserFactory $userFactory): void
    {
        $cost = 2000;

        $this->validateFunds($cost);

        $this->user->decrement('cash', $cost);
        $userFactory->levelUp($cost);
    }
}
