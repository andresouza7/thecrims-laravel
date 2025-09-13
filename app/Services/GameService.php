<?php

namespace App\Services;

use App\Models\GameState;
use App\Models\Boat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GameService
{
    protected const GAME_DAYS = 30;
    protected const GAME_DAY_DURATION_MINUTES = 1;
    protected const GAME_HOURS_PER_DAY = 24;
    protected const VITALS_RESTORE_INTERVAL_MIN = 1;

    public static function getGameTime(): string
    {
        $game = self::getGameData();

        if (!$game || $game->current_day == $game->total_days) {
            return '00:00';
        }

        // pega a data mais recente entre created_at e updated_at
        $startTime = $game->updated_at > $game->created_at ? $game->updated_at : $game->created_at;

        // converte para timestamp (segundos desde Unix epoch)
        $startTimestamp = strtotime($startTime);

        // calcula minutos decorrido desde startTime
        $elapsedMinutes = (time() - $startTimestamp) / 60;

        // progresso no dia do jogo
        $progress = fmod($elapsedMinutes, self::GAME_DAY_DURATION_MINUTES) / self::GAME_DAY_DURATION_MINUTES;
        $totalGameMinutes = $progress * self::GAME_HOURS_PER_DAY * 60;

        return sprintf('%02d:%02d', (int)($totalGameMinutes / 60), (int)($totalGameMinutes % 60));
    }

    public static function getGameDay(): int
    {
        return self::getGameData()->current_day;
    }

    public static function createRound()
    {
        self::resetData();

        BoatService::scheduleBoats();

        GameState::updateOrCreate(
            ['id' => 1],
            [
                'current_day' => 1,
                'total_days'  => self::GAME_DAYS,
                'start_time'  => time(),
            ]
        );

        User::query()->update([
            'cash' => 100000,
            'bank' => 0,
            'health' => 50,
            'max_health' => 50,
            'stamina' => 100,
            'addiction' => 0,
            'hooker_profits' => 0,
            'drug_profits' => 0,
            'boat_profits' => 0,
            'factory_profits' => 0,
            'strength' => 5,
            'intelligence' => 5,
            'charisma' => 5,
            'tolerance' => 5,

        ]);
    }

    public static function getGameData()
    {
        return GameState::first();
    }

    public static function resetData()
    {
        Boat::query()->delete();
        GameState::query()->delete();

        DB::table('user_drugs')->delete();
        DB::table('user_components')->delete();
        DB::table('user_factories')->delete();
        DB::table('user_hookers')->delete();
    }

    public static function regenerateStats(): void
    {
        $healthRegenRate  = 10; // per tick
        $staminaRegenRate = 20; // per tick
        $staminaMax       = 100;

        DB::transaction(function () use ($healthRegenRate, $staminaRegenRate, $staminaMax) {
            // Regenerate health
            DB::update("
                UPDATE users
                SET health = LEAST(health + ?, max_health)
                WHERE health < max_health
            ", [$healthRegenRate]);

            // Regenerate stamina
            DB::update("
                UPDATE users
                SET stamina = LEAST(stamina + ?, ?)
                WHERE stamina < ?
            ", [$staminaRegenRate, $staminaMax, $staminaMax]);
        });
    }

    /** Orchestrator method */
    public static function processDay()
    {
        $game = self::getGameData();

        if (!$game || $game->current_day >= $game->total_days) {
            return;
        }

        try {
            DB::transaction(function () use ($game) {
                self::applyBankInterest();
                self::processHookerIncome();
                self::processFactoryProduction();
                self::deductFactoryMaintenance();
                self::markBoatGoneForDay($game->current_day);
                self::resetDealerTransactions();
                self::grantDailyTickets();
                self::advanceDay();
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected static function applyBankInterest(float $multiplier = 1.02): void
    {
        DB::update("UPDATE users SET bank = bank * ? WHERE bank > 0", [$multiplier]);
    }

    protected static function processHookerIncome()
    {
        DB::update("
            UPDATE user_hookers uh
            JOIN hookers h ON h.id = uh.hooker_id
            SET 
                uh.available_income = uh.available_income + (h.income * uh.amount),
                uh.total_income = uh.total_income + (h.income * uh.amount)
        ");
    }

    protected static function processFactoryProduction()
    {
        DB::update("
            UPDATE user_factories uf
            JOIN factories f ON f.id = uf.factory_id
            JOIN users u ON u.id = uf.user_id
            SET uf.stash = uf.stash + (f.production * uf.level)
            WHERE u.cash >= f.maintenance
              AND f.drug_id IS NOT NULL
        ");
    }

    protected static function deductFactoryMaintenance()
    {
        DB::update("
            UPDATE users u
            JOIN (
                SELECT uf.user_id, SUM(f.maintenance) AS total_maintenance
                FROM user_factories uf
                JOIN factories f ON f.id = uf.factory_id
                JOIN users u2 ON u2.id = uf.user_id
                WHERE u2.cash >= f.maintenance
                GROUP BY uf.user_id
            ) sub ON sub.user_id = u.id
            SET u.cash = u.cash - sub.total_maintenance
        ");
    }

    protected static function markBoatGoneForDay(int $currentDay)
    {
        Boat::where('day', $currentDay)->update(['is_gone' => true]);
    }

    protected static function resetDealerTransactions(): void
    {
        User::query()->update(['dealer_transactions' => 0]);
    }

    protected static function grantDailyTickets(int $amount = 75, int $cap = 300): void
    {
        DB::update("UPDATE users SET tickets = LEAST(tickets + ?, ?)", [$amount, $cap]);
    }

    protected static function advanceDay()
    {
        $game = self::getGameData();
        $game->increment('current_day');
    }
}
