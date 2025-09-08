<?php

namespace App\Services;

use App\Models\Boat;
use App\Models\Drug;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DockService
{
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

    public function getBoatData() {}

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
