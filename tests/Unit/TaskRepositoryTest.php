<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TaskRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->repository = new TaskRepository();
    }

    public function test_can_get_all_tasks_for_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        Task::factory(3)->create(['user_id' => $user->id]);
        Task::factory(2)->create(['user_id' => $otherUser->id]);
        
        $tasks = $this->repository->getAllForUser($user->id);
        
        $this->assertCount(3, $tasks->items());
        foreach ($tasks->items() as $task) {
            $this->assertEquals($user->id, $task->user_id);
        }
    }

    public function test_can_filter_tasks_by_status(): void
    {
        $user = User::factory()->create();
        Task::factory(2)->pending()->create(['user_id' => $user->id]);
        Task::factory(1)->done()->create(['user_id' => $user->id]);
        
        $pendingTasks = $this->repository->getAllForUser($user->id, ['status' => 'pending']);
        
        $this->assertCount(2, $pendingTasks->items());
        foreach ($pendingTasks->items() as $task) {
            $this->assertEquals('pending', $task->status);
        }
    }

    public function test_can_search_tasks_by_title_and_description(): void
    {
        $user = User::factory()->create();
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Important task',
            'description' => 'This is important'
        ]);
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Regular task',
            'description' => 'This is regular'
        ]);
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Another task',
            'description' => 'Something important here'
        ]);
        
        // Search by title
        $titleResults = $this->repository->getAllForUser($user->id, ['search' => 'Important']);
        $this->assertCount(1, $titleResults->items());
        
        // Search by description
        $descriptionResults = $this->repository->getAllForUser($user->id, ['search' => 'important']);
        $this->assertCount(2, $descriptionResults->items());
    }

    public function test_can_find_task_by_id(): void
    {
        $task = Task::factory()->create();
        
        $foundTask = $this->repository->findById($task->id);
        
        $this->assertNotNull($foundTask);
        $this->assertEquals($task->id, $foundTask->id);
    }

    public function test_find_by_id_returns_null_for_non_existent_task(): void
    {
        $foundTask = $this->repository->findById(999);
        
        $this->assertNull($foundTask);
    }

    public function test_can_find_task_by_id_for_specific_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user1->id]);
        
        $foundTask = $this->repository->findByIdForUser($task->id, $user1->id);
        $notFoundTask = $this->repository->findByIdForUser($task->id, $user2->id);
        
        $this->assertNotNull($foundTask);
        $this->assertEquals($task->id, $foundTask->id);
        $this->assertNull($notFoundTask);
    }

    public function test_can_create_task(): void
    {
        $user = User::factory()->create();
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
            'user_id' => $user->id,
        ];
        
        $task = $this->repository->create($taskData);
        
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals('Test Description', $task->description);
        $this->assertEquals('pending', $task->status);
        $this->assertEquals($user->id, $task->user_id);
    }

    public function test_can_update_task(): void
    {
        $task = Task::factory()->create([
            'title' => 'Original Title',
            'status' => 'pending'
        ]);
        
        $updateData = [
            'title' => 'Updated Title',
            'status' => 'done'
        ];
        
        $updatedTask = $this->repository->update($task, $updateData);
        
        $this->assertEquals('Updated Title', $updatedTask->title);
        $this->assertEquals('done', $updatedTask->status);
    }

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();
        
        $result = $this->repository->delete($task);
        
        $this->assertTrue($result);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_can_get_tasks_by_status(): void
    {
        $user = User::factory()->create();
        Task::factory(2)->pending()->create(['user_id' => $user->id]);
        Task::factory(3)->done()->create(['user_id' => $user->id]);
        
        $pendingTasks = $this->repository->getByStatus($user->id, 'pending');
        $doneTasks = $this->repository->getByStatus($user->id, 'done');
        
        $this->assertCount(2, $pendingTasks);
        $this->assertCount(3, $doneTasks);
        
        foreach ($pendingTasks as $task) {
            $this->assertEquals('pending', $task->status);
        }
        
        foreach ($doneTasks as $task) {
            $this->assertEquals('done', $task->status);
        }
    }

    public function test_calculates_user_stats_correctly(): void
    {
        $user = User::factory()->create();
        Task::factory(2)->pending()->create(['user_id' => $user->id]);
        Task::factory(1)->inProgress()->create(['user_id' => $user->id]);
        Task::factory(3)->done()->create(['user_id' => $user->id]);
        
        $stats = $this->repository->getStats($user->id);
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('pending', $stats);
        $this->assertArrayHasKey('in_progress', $stats);
        $this->assertArrayHasKey('done', $stats);
        $this->assertArrayHasKey('completion_rate', $stats);
        
        $this->assertEquals(6, $stats['total']);
        $this->assertEquals(2, $stats['pending']);
        $this->assertEquals(1, $stats['in_progress']);
        $this->assertEquals(3, $stats['done']);
        $this->assertEquals(50.0, $stats['completion_rate']);
    }

    public function test_completion_rate_is_zero_when_no_tasks(): void
    {
        $user = User::factory()->create();
        
        $stats = $this->repository->getStats($user->id);
        
        $this->assertEquals(0, $stats['total']);
        $this->assertEquals(0, $stats['completion_rate']);
    }

    public function test_search_method_works_correctly(): void
    {
        $user = User::factory()->create();
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Important project task'
        ]);
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Regular meeting'
        ]);
        Task::factory()->create([
            'user_id' => $user->id,
            'description' => 'Very important stuff'
        ]);
        
        $results = $this->repository->search($user->id, 'Important');
        
        $this->assertCount(2, $results);
    }

    public function test_pagination_respects_limit_filter(): void
    {
        $user = User::factory()->create();
        Task::factory(15)->create(['user_id' => $user->id]);
        
        $defaultPagination = $this->repository->getAllForUser($user->id);
        $customPagination = $this->repository->getAllForUser($user->id, ['limit' => 5]);
        
        $this->assertCount(10, $defaultPagination->items()); // Default limit
        $this->assertCount(5, $customPagination->items()); // Custom limit
    }

    public function test_results_are_ordered_by_latest(): void
    {
        $user = User::factory()->create();
        $oldTask = Task::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(2)
        ]);
        $newTask = Task::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()
        ]);
        
        $tasks = $this->repository->getAllForUser($user->id);
        
        $this->assertEquals($newTask->id, $tasks->items()[0]->id);
        $this->assertEquals($oldTask->id, $tasks->items()[1]->id);
    }
}