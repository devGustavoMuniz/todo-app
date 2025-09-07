<?php

namespace App\Services;

use App\Contracts\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function getUserTasks(array $filters = []): LengthAwarePaginator
    {
        return $this->taskRepository->getAllForUser(Auth::id(), $filters);
    }

    public function createTask(array $data): Task
    {
        $data['user_id'] = Auth::id();
        
        return $this->taskRepository->create($data);
    }

    public function updateTask(Task $task, array $data): Task
    {
        $this->ensureUserOwnsTask($task);
        
        return $this->taskRepository->update($task, $data);
    }

    public function deleteTask(Task $task): bool
    {
        $this->ensureUserOwnsTask($task);
        
        return $this->taskRepository->delete($task);
    }

    public function updateTaskStatus(Task $task, string $status): Task
    {
        $this->ensureUserOwnsTask($task);
        
        if (!in_array($status, ['pending', 'in_progress', 'done'])) {
            throw new \InvalidArgumentException('Status inválido');
        }
        
        return $this->taskRepository->update($task, ['status' => $status]);
    }

    public function getUserStats(): array
    {
        return $this->taskRepository->getStats(Auth::id());
    }

    public function searchTasks(string $query): Collection
    {
        return $this->taskRepository->search(Auth::id(), $query);
    }

    public function getTaskById(int $id): ?Task
    {
        return $this->taskRepository->findByIdForUser($id, Auth::id());
    }

    private function ensureUserOwnsTask(Task $task): void
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado a esta tarefa');
        }
    }
}