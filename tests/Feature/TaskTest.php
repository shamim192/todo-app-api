<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function create_a_task()
    {
        $data = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'is_completed' => true,
        ];

        $this->postJson('/api/tasks', $data)
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Task created successfully.',
                'data' => $data
            ]);
    }

    #[Test]
    public function retrieve_all_tasks()
    {
        Task::factory()->count(5)->create();

        $this->getJson('/api/tasks')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    #[Test]
    public function retrieve_a_single_task()
    {
        $task = Task::factory()->create();

        $this->getJson("/api/tasks/{$task->id}")
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task retrieved successfully.',
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'is_completed' => $task->is_completed,
                ]
            ]);
    }

    #[Test]
    public function returns_404_if_task_not_found()
    {
        $this->getJson('/api/tasks/999')
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Task not found',
                'data' => null,
            ]);
    }

    #[Test]
    public function update_a_task()
    {
        $task = Task::factory()->create();

        $data = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'is_completed' => true,
        ];

        $this->putJson("/api/tasks/{$task->id}", $data)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task updated successfully.',
                'data' => $data,
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'is_completed' => true,
        ]);
    }

    #[Test]
    public function returns_404_when_updating_nonexistent_task()
    {
        $data = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'is_completed' => true,
        ];

        $this->putJson('/api/tasks/999', $data)
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Task not found',
                'data' => null,
            ]);
    }

    #[Test]
    public function delete_a_task()
    {
        $task = Task::factory()->create();

        $this->deleteJson("/api/tasks/{$task->id}")
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task deleted successfully.',
                'data' => null,
            ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    #[Test]
    public function returns_404_when_deleting_nonexistent_task()
    {
        $this->deleteJson('/api/tasks/999')
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Task not found',
                'data' => null,
            ]);
    }

}
