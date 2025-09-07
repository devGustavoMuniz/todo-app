<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    public function index(): Response
    {
        $stats = $this->taskService->getUserStats();
        $recentTasks = $this->taskService->getUserTasks(['limit' => 5]);

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentTasks' => $recentTasks->items(),
        ]);
    }
}
