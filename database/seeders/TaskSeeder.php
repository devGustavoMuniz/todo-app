<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário de teste
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Criar tarefas para o usuário de teste
        Task::factory(5)->pending()->forUser($testUser)->create();
        Task::factory(3)->inProgress()->forUser($testUser)->create();
        Task::factory(7)->done()->forUser($testUser)->create();

        // Criar outros usuários com tarefas
        User::factory(3)->create()->each(function ($user) {
            Task::factory(rand(3, 10))->forUser($user)->create();
        });
    }
}
