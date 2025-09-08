<?php

namespace App\Services;

use App\Interfaces\Buyable;
use App\Models\Factory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected User $user;

    public function __construct()
    {
        $user = User::first();
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
    public function buy(Buyable $item, int $quantity = 1): mixed
    {
        return DB::transaction(function () use ($item, $quantity) {
            $cost = $item->getPrice() * $quantity;

            // valida se usuário tem dinheiro suficiente
            if ($this->user->cash < $cost) {
                throw new \RuntimeException("You don't have enough cash to buy {$quantity}x {$item->name}.");
            }

            // desconta do usuário
            $this->user->decrement('cash', $cost);

            // adiciona item ao usuário
            $item->addToUser($this->user, $quantity);
        });
    }

    /**
     * Sell any Buyable item
     */
    public function sell(Buyable $item, int $quantity): mixed
    {
        return DB::transaction(function () use ($item, $quantity) {
            $stash = $item->getAmountForUser($this->user);

            // Valida se tem estoque suficiente
            if (!$stash || $stash < $quantity) {
                throw new \RuntimeException("Not enough of {$item->name} to sell.");
            }

            // if (!$stash || $stash < $quantity) {
            //     throw ValidationException::withMessages([
            //         'item' => "Not enough of {$item->name} to sell.",
            //     ]);
            // }

            $profit = $item->getPrice() * $quantity;

            // incrementa o cash do usuário
            $this->user->increment('cash', $profit);

            // remove item do usuário
            $item->removeFromUser($this->user, $quantity);

            // update player stats
            $this->user->increment('drug_profits', $profit);
        });
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
