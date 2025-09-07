<?php

namespace App\Contracts;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function getAllForUser(int $userId, array $filters = []): LengthAwarePaginator;
    public function findById(int $id): ?Task;
    public function findByIdForUser(int $id, int $userId): ?Task;
    public function create(array $data): Task;
    public function update(Task $task, array $data): Task;
    public function delete(Task $task): bool;
    public function getByStatus(int $userId, string $status): Collection;
    public function getStats(int $userId): array;
    public function search(int $userId, string $query): Collection;
}