<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Career;
use App\Livewire\Game\Nightlife;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NightlifeTest extends TestCase
{
    use RefreshDatabase;

    protected $career;
    protected $careerLevel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $this->career = Career::first();
        $this->careerLevel = $this->career->levels()->first();
    }

    protected function createTestUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'career_id' => $this->career->id,
            'career_level_id' => $this->careerLevel->id,
            'tickets' => 100,
        ], $attributes));
    }

    public function test_user_can_access_nightlife_page()
    {
        $user = $this->createTestUser();

        $response = $this->actingAs($user)->get(route('nightlife.index'));
        $response->assertStatus(200);
        $response->assertSee('Vida Noturna');
    }

    public function test_nightlife_default_active_tab_is_selection()
    {
        $user = $this->createTestUser();

        Livewire::actingAs($user)
            ->test(Nightlife::class)
            ->assertSet('activeTab', 'selection');
    }

    public function test_buy_stamina_in_boate_fails_without_tickets()
    {
        $user = $this->createTestUser([
            'tickets' => 0,
            'stamina' => 50,
            'cash' => 5000,
        ]);

        Livewire::actingAs($user)
            ->test(Nightlife::class)
            ->call('buyStamina')
            ->assertDispatched('toast', type: 'error', message: 'Você não possui ingressos (tickets) suficientes!');

        $user->refresh();
        $this->assertEquals(50, $user->stamina);
    }

    public function test_buy_stamina_in_boate_fails_if_stamina_is_full()
    {
        $user = $this->createTestUser([
            'tickets' => 5,
            'stamina' => 100,
            'cash' => 5000,
        ]);

        Livewire::actingAs($user)
            ->test(Nightlife::class)
            ->call('buyStamina')
            ->assertDispatched('toast', type: 'error', message: 'Sua stamina já está cheia!');

        $user->refresh();
        $this->assertEquals(5, $user->tickets);
    }

    public function test_buy_stamina_in_boate_success()
    {
        $user = $this->createTestUser([
            'tickets' => 5,
            'stamina' => 50,
            'cash' => 5000,
            'strength' => 20,
            'intelligence' => 20,
            'charisma' => 20,
            'tolerance' => 20,
            'addiction' => 0,
        ]);

        $expectedRespect = (int) ceil((80 / 8) + (5000 / 30000)); // stats / 8 + cash / 30000 = 10 + 0.16 = 11 respect
        $expectedCost = max(50, (int) (($expectedRespect * 3) * (50 / 100))); // max(50, 11 * 3 * 0.5) = max(50, 16.5) = 50 cost

        Livewire::actingAs($user)
            ->test(Nightlife::class)
            ->call('buyStamina')
            ->assertDispatched('toast', type: 'success');

        $user->refresh();
        $this->assertEquals(100, $user->stamina);
        $this->assertEquals(4, $user->tickets);
        $this->assertEquals(15, $user->addiction);
        $this->assertEquals(5000 - $expectedCost, $user->cash);
    }

    public function test_buy_hooker_in_mansao_fails_without_tickets()
    {
        $user = $this->createTestUser([
            'tickets' => 0,
            'stamina' => 50,
            'cash' => 5000,
        ]);

        Livewire::actingAs($user)
            ->test(Nightlife::class)
            ->call('buyHooker')
            ->assertDispatched('toast', type: 'error', message: 'Você não possui ingressos (tickets) suficientes!');

        $user->refresh();
        $this->assertEquals(50, $user->stamina);
    }

    public function test_buy_hooker_in_mansao_success_without_disease()
    {
        $user = $this->createTestUser([
            'tickets' => 50,
            'stamina' => 50,
            'cash' => 100000,
        ]);

        $lw = Livewire::actingAs($user)->test(Nightlife::class);
        $lw->call('buyHooker');

        $user->refresh();
        $this->assertEquals(100, $user->stamina);
        $this->assertEquals(49, $user->tickets);

        if ($user->in_hospital) {
            $this->assertNotNull($user->hospital_end_time);
            $this->assertEquals(0, $user->health);
        } else {
            $this->assertNull($user->hospital_end_time);
        }
    }
}
