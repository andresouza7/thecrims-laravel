<?php

namespace App\Services;

use App\Interfaces\Buyable;
use App\Models\Factory;
use App\Models\User;

class UserService
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function rewardItem(Buyable $item, int $quantity): bool
    {
        $item->addToUser($this->user, $quantity);
        return true;
    }

    /**
     * Buy or reward any Buyable item
     */
    public function buy(Buyable $item, int $quantity = 1): bool
    {
        $cost = $item->getPrice() * $quantity;

        if ($this->user->cash < $cost) return false;
        $this->user->decrement('cash', $cost);

        $item->addToUser($this->user, $quantity);

        return true;
    }

    /**
     * Sell any Buyable item
     */
    public function sell(Buyable $item, int $quantity): bool
    {
        $pivot = $item->users()->where('user_id', $this->user->id)->first();

        if (!$pivot || $pivot->pivot->amount < $quantity) return false;

        $profit = $item->getPrice() * $quantity;
        $this->user->increment('cash', $profit);

        $item->removeFromUser($this->user, $quantity);

        return true;
    }

    public function upgradeFactory(Factory $item): bool
    {
        $cost = 2000;

        if ($this->user->cash < $cost) return false;

        $this->user->decrement('cash', $cost);
        $item->upgrade($this->user, $cost);

        return true;
    }

    /**
     * Deposit cash into bank
     */
    public function deposit(int $amount): bool
    {
        if ($amount <= 0 || $this->user->cash < $amount) {
            return false;
        }

        $this->user->decrement('cash', $amount);
        $this->user->increment('bank', $amount);

        return true;
    }

    /**
     * Withdraw cash from bank
     */
    public function withdraw(int $amount): bool
    {
        if ($amount <= 0 || $this->user->bank < $amount) {
            return false;
        }

        $this->user->decrement('bank', $amount);
        $this->user->increment('cash', $amount);

        return true;
    }
}
