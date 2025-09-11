<?php

namespace App\Services;

use App\Interfaces\Buyable;
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

    public function rewardItem(Buyable $item, int $quantity): bool
    {
        $item->addToUser($this->user, $quantity);
        return true;
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
