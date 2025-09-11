<?php

namespace App\Services;

use App\Interfaces\StackableItem;
use App\Interfaces\UniqueItem;
use App\Models\Factory;
use App\Models\User;
use App\Models\UserFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function rewardItem(StackableItem $item, int $quantity): bool
    {
        $item->addToUser($this->user, $quantity);
        return true;
    }

    /**
     * Buy or reward any StackableItem item
     */
    public function buy(Model|StackableItem $item, int $quantity = 1): mixed
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
            if ($item instanceof StackableItem) $item->addToUser($this->user, $quantity);
            if ($item instanceof Model) $item->addToUser($this->user, $item);
        });
    }

    /**
     * Sell any StackableItem item
     */
    public function sell(UniqueItem|StackableItem $item, int $quantity = 1): mixed
    {
        return DB::transaction(function () use ($item, $quantity) {
            if ($item instanceof StackableItem) {
                $stash = $item->getAmountForUser($this->user);

                // Valida se tem estoque suficiente
                if (!$stash || $stash < $quantity) {
                    throw new \RuntimeException("Not enough of {$item->name} to sell.");
                }
            }

            $profit = $item->getPrice() * $quantity;

            // incrementa o cash do usuário
            $this->user->increment('cash', $profit);

            // remove item do usuário
            if ($item instanceof UniqueItem) $item->removeFromUser($item->id);
            if ($item instanceof StackableItem) $item->removeFromUser($this->user, $quantity);

            // update player stats
            $this->user->increment('drug_profits', $profit);
        });
    }

    public function upgradeFactory(UserFactory $userFactory): bool
    {
        $cost = 2000;

        if ($this->user->cash < $cost) return false;

        $this->user->decrement('cash', $cost);
        $userFactory->levelUp($cost);

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
