<?php

namespace App\Services;

use App\Models\Boat;
use App\Models\Drug;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DockService
{
    protected static array $boosts = [
        ['label' => 'Iniciante', 'min' => 0, 'max' => 9999, 'multiplier' => 1.1],
        ['label' => 'Veterano das Docas', 'min' => 10000, 'max' => 99999, 'multiplier' => 1.2],
        ['label' => 'Contrabandista Experiente', 'min' => 100000, 'max' => INF, 'multiplier' => 1.3],
    ];

    public static function getBoatSellBoost(int $totalSold): ?array
    {
        return current(array_filter(self::$boosts, fn($b) => $totalSold >= $b['min'] && $totalSold <= $b['max'])) ?: null;
    }

    public static function getNextBoatBoostInfo(int $totalSold): array
    {
        $index = array_search(self::getBoatSellBoost($totalSold), self::$boosts, true);
        $next = self::$boosts[$index + 1] ?? null;

        return $next
            ? ['nextBoostLabel' => $next['label'], 'remaining' => $next['min'] - $totalSold]
            : ['nextBoostLabel' => null, 'remaining' => 0, 'message' => 'Nível máximo de boost alcançado!'];
    }

    public static function getSellPrice(float $price, float $multiplier): int
    {
        return (int) floor($price * $multiplier);
    }

    public static function scheduleBoats(): void
    {
        $days = [3, 8, 13, 18, 23, 28];

        // Fetch drug IDs once
        $drugs = Drug::pluck('id')->all();

        if (empty($drugs)) {
            logger()->warning("No drugs found.");
            return;
        }

        // Helper to get a random drug id
        $getRandomDrugId = fn() => $drugs[array_rand($drugs)];

        // Prepare and insert boats
        $boatEntries = collect($days)->map(fn($day) => [
            'day' => $day,
            'drug_id' => $getRandomDrugId(),
            'is_gone' => false
        ])->all();

        try {
            DB::table('boats')->insert($boatEntries);
            logger()->info("Boats scheduled successfully with random drugs.");
        } catch (\Throwable $e) {
            logger()->error("Error inserting boats: " . $e->getMessage());
        }
    }

    public static function getBoatData(): array
    {
        $user = User::first();

        // Current game day
        // $currentDay = Boat::query()
        //     ->selectRaw('MAX(day) as current_day') 
        //     ->value('current_day');
        $currentDay = 3;

        // Current boat with drug
        $currentBoat = Boat::with('drug') // assuming Boat has belongsTo(Drug::class, 'drug_id', 'id')
            ->where('day', $currentDay)
            ->where('is_gone', false)
            ->orderBy('day', 'asc')
            ->first();

        // Owned amount
        $ownedAmount = $currentBoat->drug->getAmountForUser($user);

        // Next boat
        $nextBoat = Boat::with('drug')
            ->where('day', '>', $currentDay)
            ->where('is_gone', false)
            ->orderBy('day', 'asc')
            ->first();

        // Past boats
        $pastBoats = Boat::with('drug')
            ->where('is_gone', true)
            ->orderBy('day', 'asc')
            ->get()
            ->map(fn($b) => [
                'day' => $b->day,
                'drug_name' => $b->drug->name,
            ])
            ->all();

        // Boat profits for player
        $boatProfits = $user->boat_profits ?? 0;

        // Return combined data as normal PHP array
        return [
            'current_boat' => $currentBoat ? $currentBoat->toArray() : null,
            'next_boat'    => $nextBoat ? $nextBoat->toArray() : null,
            'past_boats'   => $pastBoats,
            'boat_profits' => $boatProfits,
            'owned_amount' => $ownedAmount,
        ];
    }

    public static function sellDrugOnBoat(Drug $drug, Boat $boat, int $amount): void
    {
        $user = User::first();

        DB::transaction(function () use ($user, $drug, $boat, $amount) {
            // Validate that boat day matches current day
            // $currentDay = DB::table('game_state')->value('current_day');
            $currentDay = 3;
            if ($boat->day !== $currentDay) {
                throw ValidationException::withMessages([
                    'boat' => "Boat day {$boat->day} does not match current game day {$currentDay}.",
                ]);
            }

            // Calculate profits
            $profits = $drug->price * $amount;

            $userService = new UserService($user);
            $userService->sell($drug, $amount);

            // Update player stats
            $user->increment('boat_profits', $profits);
        });
    }
}
