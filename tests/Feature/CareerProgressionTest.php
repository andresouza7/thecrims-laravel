<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Career;
use App\Models\CareerLevel;
use App\Models\Drug;
use App\Models\Hooker;
use App\Models\Equipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareerProgressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_user_starts_with_one_thousand_cash()
    {
        $user = User::factory()->create();
        $user->refresh();
        $this->assertEquals(1000, $user->cash);
    }

    public function test_careers_have_exactly_ten_levels_each()
    {
        $this->assertEquals(6, Career::count());
        $this->assertEquals(60, CareerLevel::count()); // 6 careers * 10 levels

        Career::all()->each(function (Career $career) {
            $this->assertEquals(10, $career->levels()->count());
        });
    }

    public function test_game_has_exactly_ten_drugs_ten_hookers_ten_weapons_and_ten_armors()
    {
        $this->assertEquals(10, Drug::count());
        $this->assertEquals(10, Hooker::count());
        $this->assertEquals(10, Equipment::where('type', 'armor')->count());
        $this->assertEquals(10, Equipment::whereNot('type', 'armor')->count());
    }

    public function test_first_level_of_all_careers_has_no_requirements_and_no_rewards()
    {
        CareerLevel::where('level', 1)->get()->each(function (CareerLevel $level) {
            $this->assertEquals(0, $level->params()->count());
        });
    }

    public function test_higher_levels_of_all_careers_have_cash_and_available_stats_rewards()
    {
        CareerLevel::where('level', '>', 1)->get()->each(function (CareerLevel $level) {
            $cashReward = $level->params()
                ->join('game_params', 'game_params.id', '=', 'career_level_params.game_param_id')
                ->where('game_params.name', 'cash')
                ->where('game_params.type', 'reward')
                ->first();

            $statsReward = $level->params()
                ->join('game_params', 'game_params.id', '=', 'career_level_params.game_param_id')
                ->where('game_params.name', 'available_stats')
                ->where('game_params.type', 'reward')
                ->first();

            $this->assertNotNull($cashReward);
            $this->assertNotNull($statsReward);
            $this->assertGreaterThan(0, $cashReward->value);
            $this->assertGreaterThan(0, $statsReward->value);
        });
    }
}
