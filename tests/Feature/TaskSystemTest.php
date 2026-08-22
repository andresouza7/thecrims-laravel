<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskParam;
use App\Models\GameParam;
use App\Models\Drug;
use App\Models\Equipment;
use App\Models\Hooker;
use App\Models\Component;
use App\Services\TaskService;
use App\Livewire\Game\Street;
use App\Livewire\Game\Career\About;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class TaskSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // DatabaseSeeder will run our TaskSeeder too
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_tasks_seeder_populates_categories_and_tasks()
    {
        $this->assertEquals(5, TaskCategory::count());
        $this->assertEquals(20, Task::count()); // 5 categories * 4 tasks

        $firstCategory = TaskCategory::first();
        $this->assertEquals('Iniciante no Crime', $firstCategory->name);
        $this->assertEquals(4, $firstCategory->tasks()->count());
    }

    public function test_activate_and_pause_task_category()
    {
        $user = User::factory()->create();
        $category = TaskCategory::first();

        // Initially no active category
        $this->assertNull($user->active_task_category_id);

        // Test Livewire component activation
        Livewire::actingAs($user)
            ->test(Street::class)
            ->call('startCategory', $category->id)
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals($category->id, $user->active_task_category_id);

        // Test Livewire component pause
        Livewire::actingAs($user)
            ->test(Street::class)
            ->call('pauseCategory')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertNull($user->active_task_category_id);
    }

    public function test_task_strict_order_constraint()
    {
        $user = User::factory()->create();
        $category = TaskCategory::first();
        $tasks = $category->tasks;
        $task1 = $tasks[0];
        $task2 = $tasks[1];

        $taskService = new TaskService();

        // Activating category
        $user->active_task_category_id = $category->id;
        $user->save();

        // Task 1 and 2 requirements are not met yet, so can't complete either
        $this->assertFalse($taskService->canCompleteTask($user, $task1));
        $this->assertFalse($taskService->canCompleteTask($user, $task2));

        // Meet requirements for BOTH task 1 and task 2
        // Task 1: single_robbery_count = 5
        // Task 2: cash = 2000, single_robbery_count = 10
        $user->cash = 5000;
        $user->save();

        // Fake robbery counts for the user
        $robbery = \App\Models\Robbery::first();
        DB::table('user_robberies')->insert([
            'user_id' => $user->id,
            'robbery_id' => $robbery->id,
            'success_count' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Now, Task 1 requirements are met. Can we complete Task 1? Yes.
        $this->assertTrue($taskService->canCompleteTask($user, $task1));

        // Can we complete Task 2 even though Task 1 is not completed yet?
        // Its requirements are met, but Task 1 is still pending!
        // According to order progression constraint, it should be FALSE.
        $this->assertFalse($taskService->canCompleteTask($user, $task2));

        // Complete Task 1
        $taskService->completeTask($user, $task1);
        $user->refresh();

        // Now Task 1 is completed. Can we complete Task 2 now? Yes!
        $this->assertTrue($taskService->canCompleteTask($user, $task2));
    }

    public function test_granting_task_rewards_including_available_stats()
    {
        $user = User::factory()->create(['cash' => 0, 'available_stats' => 0]);
        $category = TaskCategory::first();
        $task1 = $category->tasks[0];

        $taskService = new TaskService();

        // Granting task 1 rewards (Cash = 500, Available stats = 5)
        $this->assertEquals(0, $user->cash);
        $this->assertEquals(0, $user->available_stats);

        $taskService->grantRewards($user, $task1);

        $user->refresh();
        $this->assertEquals(500, $user->cash);
        $this->assertEquals(5, $user->available_stats);
    }

    public function test_street_route_access_controls_jail_and_hospital()
    {
        $user = User::factory()->create();

        // 1. Healthy user can access street
        $this->assertTrue($user->canAccessPath('street'));

        // 2. User in Jail cannot access street
        $user->jail_end_time = now()->addMinutes(5);
        $user->save();
        $this->assertFalse($user->canAccessPath('street'));

        // 3. User in Hospital cannot access street
        $user->jail_end_time = null;
        $user->hospital_end_time = now()->addMinutes(5);
        $user->save();
        $this->assertFalse($user->canAccessPath('street'));
    }

    public function test_stat_points_allocation_in_career()
    {
        $user = User::factory()->create([
            'available_stats' => 10,
            'strength' => 50,
            'intelligence' => 50,
            'charisma' => 50,
            'tolerance' => 50,
        ]);

        // Allocating 5 points to strength
        Livewire::actingAs($user)
            ->test(About::class)
            ->set('statToAllocate', 'strength')
            ->set('statQuantity', 5)
            ->call('distributeStats')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals(5, $user->available_stats);
        $this->assertEquals(55, $user->strength);

        // Try allocating more points than available (should throw validation error/exception inside the component)
        Livewire::actingAs($user)
            ->test(About::class)
            ->set('statToAllocate', 'intelligence')
            ->set('statQuantity', 10) // Only 5 available
            ->call('distributeStats');

        // Available stats should still be 5
        $user->refresh();
        $this->assertEquals(5, $user->available_stats);
    }
}
