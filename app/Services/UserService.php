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

        $production = UserFactory::where('user_id', $userId)
            ->where('stash', '>', 0)
            ->exists();

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
            ON DUPLICATE KEY UPDATE
                amount = amount + VALUES(amount)
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

        $this->user->increment('cash', $income);
        $this->user->increment('hooker_profits', $income);
        DB::table('user_hookers')->where('user_id', $this->user->id)->update(['available_income' => 0]);
    }

    public function fight(User $victim)
    {
        $attacker = $this->user;

        if ($attacker->health < 10) {
            throw new \Exception("Too weak to perform this action.");
        }

        // Check stamina
        $staminaCost = 20;
        if ($attacker->stamina < $staminaCost) {
            throw new \Exception("Not enough stamina to perform this action.");
        }

        // Reduce attacker stamina
        $attacker->stamina -= $staminaCost;

        // Compare assault powers
        $attackerPower = $attacker->assault_power;
        $victimPower   = $victim->assault_power;

        // Randomize slightly to avoid deterministic outcome
        $attackerRoll = $attackerPower + rand(0, 10);
        $victimRoll   = $victimPower + rand(0, 10);

        $winner = $attackerRoll >= $victimRoll ? $attacker : $victim;
        $loser  = $winner->is($attacker) ? $victim : $attacker;

        // Apply health loss
        $winner->health = max(1, $winner->health - rand(5, 15)); // Winner loses some health
        $loser->health  = 0; // Loser is killed

        // If attacker wins, reward them
        if ($winner->is($attacker)) {
            $rewardCash = (int) ($victim->cash * 0.1); // Take victim's cash
            $attacker->cash += $rewardCash;
            $victim->cash  -= $rewardCash;

            // Reward stats
            $statReward = 2; // Example: 2 points each
            $attacker->strength     += $statReward;
            $attacker->intelligence += $statReward;
            $attacker->charisma     += $statReward;
            $attacker->tolerance    += $statReward;

            // Register kill
            DB::table('user_kills')->insert([
                'killer_id' => $attacker->id,
                'victim_id' => $victim->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Persist changes
        $attacker->save();
        $victim->save();

        return [
            'winner' => $winner->id,
            'loser' => $loser->id,
            'rewardCash' => $winner->is($attacker) ? $rewardCash : 0,
        ];
    }
}
