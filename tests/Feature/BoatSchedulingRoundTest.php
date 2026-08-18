<?php

use App\Models\Boat;
use App\Models\Drug;
use App\Services\GameService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('boats are scheduled when a new round is created', function () {
    // 1. Seed some drugs first because scheduling depends on them
    $drug1 = Drug::factory()->create(['name' => 'Maconha', 'price' => 8]);
    $drug2 = Drug::factory()->create(['name' => 'Metanfetamina', 'price' => 35]);

    // Verify there are no boats initially
    expect(Boat::count())->toBe(0);

    // 2. Create the round
    GameService::createRound();

    // 3. Verify boats are scheduled
    $expectedDays = [1, 2, 3, 8, 13, 18, 23, 28];
    expect(Boat::count())->toBe(count($expectedDays));

    foreach ($expectedDays as $day) {
        $boat = Boat::where('day', $day)->first();
        expect($boat)->not->toBeNull();
        expect($boat->is_gone)->toBeFalse();
        expect($boat->drug_id)->not->toBeNull();
    }
});

test('getBoatData and sellToBoat work with correct pricing and payouts', function () {
    $user = \App\Models\User::factory()->create(['cash' => 1000, 'boat_profits' => 0]);
    $drug = Drug::factory()->create(['name' => 'Cocaína', 'price' => 100]);

    // Create a boat for Day 1
    GameService::createRound();
    $boat = Boat::where('day', 1)->first();
    $boat->update(['drug_id' => $drug->id]);

    // Attach drug to user with enough amount (must be after createRound which resets user data)
    $drug->addToUser($user, 50);

    $boatService = new \App\Services\BoatService($user, new \App\Services\ActionService($user));

    // Get boat data
    $data = $boatService->getBoatData();
    expect($data['boats'])->toHaveCount(1);

    $retrievedBoat = $data['boats']->first();
    // 1.1x boost for beginner level (0-9999 boat_profits)
    // 100 * 1.1 = 110
    expect($retrievedBoat->price)->toBe(110);

    // Sell to boat
    $boatService->sellToBoat($boat, 10);

    // Starting cash: 100000 + (110 * 10) = 101100
    expect($user->fresh()->cash)->toBe(101100);
    expect($user->fresh()->boat_profits)->toBe(1100);
    expect($drug->getAmountForUser($user))->toBe(40);
});
