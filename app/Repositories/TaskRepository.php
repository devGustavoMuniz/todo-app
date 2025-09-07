<?php

namespace App\Repositories;

use App\Contracts\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllForUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = Task::forUser($userId)->latest();

        // Aplicar filtros
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->byStatus($filters['status']);
        }

        if (isset($filters['search']) && $filters['search'] !== '') {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        $perPage = $filters['limit'] ?? 10;
        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Task
    {
        return Task::find($id);
    }

    public function findByIdForUser(int $id, int $userId): ?Task
    {
        return Task::where('id', $id)->forUser($userId)->first();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->fresh();
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    public function getByStatus(int $userId, string $status): Collection
    {
        return Task::forUser($userId)->byStatus($status)->get();
    }

    public function getStats(int $userId): array
    {
        $tasks = Task::forUser($userId);

        return [
            'total' => $tasks->count(),
            'pending' => $tasks->pending()->count(),
            'in_progress' => $tasks->inProgress()->count(),
            'done' => $tasks->done()->count(),
            'completion_rate' => $this->calculateCompletionRate($userId),
        ];
    }

    public function search(int $userId, string $query): Collection
    {
        return Task::forUser($userId)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->get();
    }

    private function calculateCompletionRate(int $userId): float
    {
        $total = Task::forUser($userId)->count();
        $completed = Task::forUser($userId)->done()->count();

        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }
}