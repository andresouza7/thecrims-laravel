<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Robbery;
use App\Models\Drug;
use App\Models\Component;
use App\Services\GameService;
use App\Services\ActionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RobberyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_robbery_seeder_populates_progressive_entries()
    {
        $this->assertEquals(21, Robbery::count());

        $robberies = Robbery::orderBy('required_power', 'asc')->get();

        // Check first entry
        $first = $robberies->first();
        $this->assertEquals('Pedir esmola fingindo ser mudo', $first->description);
        $this->assertEquals(3, $first->required_power);
        $this->assertEquals(10, $first->required_stamina);
        $this->assertEquals('solo', $first->type);
        $this->assertEquals(50, $first->cash);
        $this->assertEmpty($first->drugs);
        $this->assertEmpty($first->components);

        // Check progression
        $last = $robberies->last();
        $this->assertEquals('Saquear o cofre de alta segurança da Reserva Federal', $last->description);
        $this->assertEquals(55000, $last->required_power);
        $this->assertEquals(70, $last->required_stamina);
        $this->assertEquals(1500000, $last->cash);
    }

    public function test_robbery_success_and_rewards()
    {
        $user = User::factory()->create([
            'stamina' => 100,
            'strength' => 500,
            'intelligence' => 500,
            'tolerance' => 500,
            'charisma' => 500,
            'cash' => 1000,
        ]);

        $maconha = Drug::where('name', 'Maconha')->first();
        $this->assertNotNull($maconha);

        $robbery = Robbery::create([
            'description' => 'Test Robbery',
            'required_power' => 100,
            'required_stamina' => 30,
            'type' => 'solo',
            'cash' => 5000,
            'drugs' => [['drug_id' => $maconha->id, 'amount' => 10]],
            'components' => [],
        ]);

        $actionService = new ActionService($user);

        // Success chance should be > 100% since user single_robbery_power is way higher than 100
        $this->assertGreaterThanOrEqual(100, $actionService->calculateSuccessChance($robbery));

        // Execute robbery
        $result = $actionService->executeRobbery($robbery);

        $this->assertTrue($result['success']);
        $this->assertNotEmpty($result['message']);
        
        // Assert user stats changed
        $user->refresh();
        $this->assertEquals(70, $user->stamina); // 100 - 30
        $this->assertEquals(6000, $user->cash); // 1000 + 5000

        // Assert drug added
        $userDrug = $user->drugs()->where('drug_id', $maconha->id)->first();
        $this->assertNotNull($userDrug);
        $this->assertEquals(10, $userDrug->pivot->amount);

        // Assert robbery count incremented
        $pivot = \DB::table('user_robberies')->where('user_id', $user->id)->where('robbery_id', $robbery->id)->first();
        $this->assertNotNull($pivot);
        $this->assertEquals(1, $pivot->success_count);
        $this->assertEquals(0, $pivot->fail_count);
    }

    public function test_robbery_failure_sends_user_to_jail()
    {
        // Low stats to guarantee failure
        $user = User::factory()->create([
            'stamina' => 100,
            'strength' => 1,
            'intelligence' => 1,
            'tolerance' => 1,
            'charisma' => 1,
            'cash' => 1000,
        ]);

        $robbery = Robbery::create([
            'description' => 'Test Robbery',
            'required_power' => 99999, // Impossible requirement
            'required_stamina' => 30,
            'type' => 'solo',
            'cash' => 5000,
            'drugs' => [],
            'components' => [],
        ]);

        $actionService = new ActionService($user);

        // Chance should be close to 0%
        $this->assertEquals(0, $actionService->calculateSuccessChance($robbery));

        // Execute robbery
        $result = $actionService->executeRobbery($robbery);

        $this->assertFalse($result['success']);
        $this->assertNotEmpty($result['message']);

        // Assert user went to jail and stamina deducted
        $user->refresh();
        $this->assertEquals(70, $user->stamina); // 100 - 30
        $this->assertTrue($user->in_jail);

        // Assert fail count incremented
        $pivot = \DB::table('user_robberies')->where('user_id', $user->id)->where('robbery_id', $robbery->id)->first();
        $this->assertNotNull($pivot);
        $this->assertEquals(0, $pivot->success_count);
        $this->assertEquals(1, $pivot->fail_count);
    }
}
