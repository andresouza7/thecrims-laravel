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

    public function getUser()
    {
        return $this->user;
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

    public function collectFactoryProduction()
    {
        $userId = $this->user->id;
        $production = UserFactory::where('user_id', $userId)->where('stash', '>', 0)->exists();

        if (!$production) {
            throw new \RuntimeException("Nothing to collect.");
        }

        DB::transaction(function () use ($userId) {
            DB::statement("
            INSERT INTO user_drugs (user_id, drug_id, amount)
            SELECT
                uf.user_id,
                f.drug_id,
                uf.stash
            FROM user_factories uf
            JOIN factories f ON f.id = uf.factory_id
            WHERE uf.user_id = ? AND uf.stash > 0
            ON CONFLICT (user_id, drug_id)
            DO UPDATE SET
                amount = user_drugs.amount + EXCLUDED.amount
        ", [$userId]);

            DB::statement("
            UPDATE user_factories
            SET stash = 0
            WHERE user_id = ? AND stash > 0
        ", [$userId]);
        });
    }

    public function collectHookerIncome()
    {
        $income = $this->user->hooker_income;

        if (!$income || $income == 0) {
            throw new \RuntimeException("Nothing to collect.");
        }

        return;

        $this->user->increment('cash', $income);
        $this->user->increment('hooker_profits', $income);
        DB::table('user_hookers')->where('user_id', $this->user->id)->update(['available_income' => 0]);
    }
}
