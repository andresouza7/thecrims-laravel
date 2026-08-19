<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Career;
use App\Services\GameService;
use App\Services\ActionService;
use App\Livewire\Game\Hospital;
use App\Livewire\Game\Jail;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalJailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_hospital_buy_stamina_success()
    {
        // respect calculation: (200 * 4) / 8 + 1000 / 30000 = 100 + 0.033 = 100.033
        // ceil(100.033) = 101 respect.
        // Cost = max(100, 101 * 5) = 505
        $user = User::factory()->create([
            'stamina' => 10,
            'addiction' => 5,
            'strength' => 200,
            'intelligence' => 200,
            'charisma' => 200,
            'tolerance' => 200,
            'cash' => 1000,
        ]);

        $this->actingAs($user);

        Livewire::test(Hospital::class)
            ->call('buyStamina')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals(100, $user->stamina);
        $this->assertEquals(20, $user->addiction); // 5 + 15
        $this->assertEquals(495, $user->cash); // 1000 - 505
    }

    public function test_hospital_buy_stamina_insufficient_funds()
    {
        // Cost = 505
        $user = User::factory()->create([
            'stamina' => 10,
            'addiction' => 5,
            'strength' => 200,
            'intelligence' => 200,
            'charisma' => 200,
            'tolerance' => 200,
            'cash' => 200,
        ]);

        $this->actingAs($user);

        Livewire::test(Hospital::class)
            ->call('buyStamina')
            ->assertDispatched('toast', type: 'error');

        $user->refresh();
        $this->assertEquals(10, $user->stamina);
        $this->assertEquals(5, $user->addiction);
        $this->assertEquals(200, $user->cash);
    }

    public function test_hospital_buy_detox_success()
    {
        // respect calculation: (200 * 4) / 8 + 3000 / 30000 = 100 + 0.1 = 100.1
        // ceil(100.1) = 101 respect.
        // Cost = max(200, 101 * 10) = 1010
        $user = User::factory()->create([
            'addiction' => 50,
            'strength' => 200,
            'intelligence' => 200,
            'charisma' => 200,
            'tolerance' => 200,
            'cash' => 3000,
        ]);

        $this->actingAs($user);

        Livewire::test(Hospital::class)
            ->call('buyDetox')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals(0, $user->addiction);
        $this->assertEquals(1990, $user->cash); // 3000 - 1010
    }

    public function test_hospital_buy_detox_insufficient_funds()
    {
        // Cost = 1010
        $user = User::factory()->create([
            'addiction' => 50,
            'strength' => 200,
            'intelligence' => 200,
            'charisma' => 200,
            'tolerance' => 200,
            'cash' => 200,
        ]);

        $this->actingAs($user);

        Livewire::test(Hospital::class)
            ->call('buyDetox')
            ->assertDispatched('toast', type: 'error');

        $user->refresh();
        $this->assertEquals(50, $user->addiction);
        $this->assertEquals(200, $user->cash);
    }

    public function test_hospital_buy_detox_already_clean()
    {
        $user = User::factory()->create([
            'addiction' => 0,
            'strength' => 200,
            'intelligence' => 200,
            'charisma' => 200,
            'tolerance' => 200,
            'cash' => 3000,
        ]);

        $this->actingAs($user);

        Livewire::test(Hospital::class)
            ->call('buyDetox')
            ->assertDispatched('toast', type: 'error');

        $user->refresh();
        $this->assertEquals(0, $user->addiction);
        $this->assertEquals(3000, $user->cash);
    }

    public function test_hospital_release_action()
    {
        $user = User::factory()->create([
            'hospital_end_time' => now()->addMinutes(2),
        ]);

        $this->actingAs($user);
        $this->assertTrue($user->in_hospital);

        // Move time past hospital release time
        $this->travel(3)->minutes();

        Livewire::test(Hospital::class)
            ->call('release')
            ->assertDispatched('toast', type: 'success');

        $user->refresh();
        $this->assertFalse($user->in_hospital);
    }

    public function test_jail_bribe_and_release_actions()
    {
        // Stats: strength = 10, intelligence = 10, tolerance = 10.
        // assault_power: round(((10 * 0.05 + 10 * 0.25 + 10 * 0.7) / 2) * 1) = round((10 / 2)) = 5.
        // jail_release_cost = 5 * 1000 = 5000.
        $user = User::factory()->create([
            'jail_end_time' => now()->addMinutes(2),
            'cash' => 10000,
            'strength' => 10,
            'intelligence' => 10,
            'charisma' => 10,
            'tolerance' => 10,
        ]);

        $this->actingAs($user);
        $this->assertTrue($user->in_jail);

        // Bribe
        Livewire::test(Jail::class)
            ->call('bribe')
            ->assertDispatched('toast', type: 'success');

        $user->refresh();
        $this->assertFalse($user->in_jail);
        $this->assertEquals(5000, $user->cash); // 10000 - 5000
    }
}
