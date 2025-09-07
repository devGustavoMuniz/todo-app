<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_authenticated_user_can_view_tasks_page(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/tasks');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) => 
                    $page->component('Tasks/Index')
                         ->has('tasks')
                         ->has('stats')
                         ->has('filters')
                );
    }

    public function test_guest_cannot_access_tasks_page(): void
    {
        $response = $this->get('/tasks');
        
        $response->assertRedirect('/login');
    }

    public function test_user_can_create_a_task(): void
    {
        $user = User::factory()->create();
        
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
        ];
        
        $response = $this->actingAs($user)->post('/tasks', $taskData);
        
        $response->assertRedirect('/tasks');
        $response->assertSessionHas('message', 'Tarefa criada com sucesso!');
        
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
            'user_id' => $user->id,
        ]);
    }

    public function test_task_creation_requires_title(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/tasks', [
            'description' => 'Test Description',
            'status' => 'pending',
        ]);
        
        $response->assertSessionHasErrors(['title']);
    }

    public function test_task_creation_requires_valid_status(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'Test Task',
            'status' => 'invalid_status',
        ]);
        
        $response->assertSessionHasErrors(['status']);
    }

    public function test_user_can_update_their_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        
        $updateData = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'status' => 'in_progress',
        ];
        
        $response = $this->actingAs($user)->put("/tasks/{$task->id}", $updateData);
        
        $response->assertRedirect('/tasks');
        $response->assertSessionHas('message', 'Tarefa atualizada com sucesso!');
        
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'status' => 'in_progress',
        ]);
    }

    public function test_user_cannot_update_another_users_task(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user1->id]);
        
        $response = $this->actingAs($user2)->put("/tasks/{$task->id}", [
            'title' => 'Hacked Task',
            'status' => 'done',
        ]);
        
        $response->assertStatus(403);
        
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
            'title' => 'Hacked Task',
        ]);
    }

    public function test_user_can_view_their_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->get("/tasks/{$task->id}");
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Tasks/Show')
                         ->where('task.id', $task->id)
                         ->where('task.title', $task->title)
                );
    }

    public function test_user_cannot_view_another_users_task(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user1->id]);
        
        $response = $this->actingAs($user2)->get("/tasks/{$task->id}");
        
        $response->assertStatus(403);
    }

    public function test_user_can_delete_their_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->delete("/tasks/{$task->id}");
        
        $response->assertRedirect('/tasks');
        $response->assertSessionHas('message', 'Tarefa excluída com sucesso!');
        
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_delete_another_users_task(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user1->id]);
        
        $response = $this->actingAs($user2)->delete("/tasks/{$task->id}");
        
        $response->assertStatus(403);
        
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'deleted_at' => null,
        ]);
    }

    public function test_user_can_update_task_status(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->pending()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->patch("/tasks/{$task->id}/status", [
            'status' => 'done'
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('message', 'Status atualizado com sucesso!');
        
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'done',
        ]);
    }

    public function test_tasks_are_paginated(): void
    {
        $user = User::factory()->create();
        Task::factory(15)->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->get('/tasks');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Tasks/Index')
                         ->has('tasks.data', 10) // Default pagination
                         ->has('tasks.links')
                );
    }

    public function test_tasks_can_be_filtered_by_status(): void
    {
        $user = User::factory()->create();
        Task::factory(2)->pending()->create(['user_id' => $user->id]);
        Task::factory(3)->done()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->get('/tasks?status=pending');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Tasks/Index')
                         ->has('tasks.data', 2)
                );
    }

    public function test_tasks_can_be_searched(): void
    {
        $user = User::factory()->create();
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Important task'
        ]);
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Regular task'
        ]);
        
        $response = $this->actingAs($user)->get('/tasks?search=Important');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Tasks/Index')
                         ->has('tasks.data', 1)
                );
    }

    public function test_task_statistics_are_calculated_correctly(): void
    {
        $user = User::factory()->create();
        Task::factory(2)->pending()->create(['user_id' => $user->id]);
        Task::factory(1)->inProgress()->create(['user_id' => $user->id]);
        Task::factory(3)->done()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->get('/tasks');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Tasks/Index')
                         ->where('stats.total', 6)
                         ->where('stats.pending', 2)
                         ->where('stats.in_progress', 1)
                         ->where('stats.done', 3)
                         ->where('stats.completion_rate', 50.0)
                );
    }

    public function test_only_user_tasks_are_displayed(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Task::factory(3)->create(['user_id' => $user1->id]);
        Task::factory(2)->create(['user_id' => $user2->id]);
        
        $response = $this->actingAs($user1)->get('/tasks');
        
        $response->assertStatus(200)
                ->assertInertia(fn (Assert $page) =>
                    $page->component('Tasks/Index')
                         ->has('tasks.data', 3)
                         ->where('stats.total', 3)
                );
    }
}