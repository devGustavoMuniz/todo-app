<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Dashboard')
                         ->has('stats')
                         ->has('recentTasks')
                );
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');
        
        $response->assertRedirect('/login');
    }

    public function test_dashboard_displays_correct_stats(): void
    {
        $user = User::factory()->create();
        Task::factory(2)->pending()->create(['user_id' => $user->id]);
        Task::factory(1)->inProgress()->create(['user_id' => $user->id]);
        Task::factory(3)->done()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Dashboard')
                         ->where('stats.total', 6)
                         ->where('stats.pending', 2)
                         ->where('stats.in_progress', 1)
                         ->where('stats.done', 3)
                         ->where('stats.completion_rate', 50.0)
                );
    }

    public function test_dashboard_shows_recent_tasks(): void
    {
        $user = User::factory()->create();
        $tasks = Task::factory(8)->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Dashboard')
                         ->has('recentTasks', 5) // Limited to 5 recent tasks
                );
    }

    public function test_dashboard_only_shows_user_own_data(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Task::factory(3)->create(['user_id' => $user1->id]);
        Task::factory(5)->create(['user_id' => $user2->id]);
        
        $response = $this->actingAs($user1)->get('/dashboard');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Dashboard')
                         ->where('stats.total', 3)
                         ->has('recentTasks', 3)
                );
    }

    public function test_dashboard_handles_empty_state(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Dashboard')
                         ->where('stats.total', 0)
                         ->where('stats.pending', 0)
                         ->where('stats.in_progress', 0)
                         ->where('stats.done', 0)
                         ->where('stats.completion_rate', 0)
                         ->has('recentTasks', 0)
                );
    }

    public function test_dashboard_orders_recent_tasks_by_latest(): void
    {
        $user = User::factory()->create();
        $oldTask = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Old Task',
            'created_at' => now()->subDays(2)
        ]);
        $newTask = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'New Task',
            'created_at' => now()
        ]);
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Dashboard')
                         ->where('recentTasks.0.title', 'New Task')
                         ->where('recentTasks.1.title', 'Old Task')
                );
    }

    public function test_dashboard_completion_rate_calculation(): void
    {
        $user = User::factory()->create();
        
        // Test 100% completion rate
        Task::factory(4)->done()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertInertia(fn (Assert $page) =>
            $page->where('stats.completion_rate', 100)
        );
        
        // Add pending tasks to test partial completion
        Task::factory(6)->pending()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertInertia(fn (Assert $page) =>
            $page->where('stats.completion_rate', 40) // 4 done out of 10 total
        );
    }

    public function test_dashboard_recent_tasks_include_all_necessary_data(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Sample Task',
            'description' => 'Sample Description',
            'status' => 'pending'
        ]);
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Dashboard')
                         ->where('recentTasks.0.id', $task->id)
                         ->where('recentTasks.0.title', 'Sample Task')
                         ->where('recentTasks.0.description', 'Sample Description')
                         ->where('recentTasks.0.status', 'pending')
                         ->has('recentTasks.0.status_label')
                         ->has('recentTasks.0.created_at')
                         ->has('recentTasks.0.updated_at')
                );
    }
}