<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreTask()
    {
        $response = $this->postJson('/api/tasks', [
            'title'       => 'New Task',
            'description' => 'Task description',
            'status'      => 2,
            'priority'    => 1,
            'deadline'    => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
        ]);
        $response->assertStatus(201);
    }

    public function testStoreTaskFails()
    {
        $response = $this->postJson('/api/tasks', []);

        $response->assertStatus(422);
    }

    public function testIndexTasks()
    {
        $tasks = Task::factory()->count(3)->create([
            'title'       => 'Sample Task',
            'description' => 'Sample description',
            'status'      => 2,
            'priority'    => 2,
            'deadline'    => Carbon::now()->addDays(3),
        ]);
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200);
    }

    public function testShowTask()
    {
        $task = Task::factory()->create([
            'title'       => 'Sample Task',
            'description' => 'Sample description',
            'status'      => 2,
            'priority'    => 2,
            'deadline'    => Carbon::now()->addDays(3),
        ]);

        $response = $this->getJson('/api/tasks/' . $task->id);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id'          => $task->id,
                'title'       => 'Sample Task',
                'description' => 'Sample description',
                'status'      => 2,
                'priority'    => 2,
                'deadline'    => $task->deadline->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function testUpdateTask()
    {
        $task = Task::factory()->create([
            'title'       => 'Old Title',
            'description' => 'Old description',
            'status'      => 3,
            'priority'    => 3,
            'deadline'    => Carbon::now()->addDays(1),
        ]);

        $response = $this->putJson('/api/tasks/' . $task->id, [
            'title'       => 'Updated Title',
            'description' => 'Updated description',
            'status'      => 2,
            'priority'    => 1,
            'deadline'    => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id'          => $task->id,
                'title'       => 'Updated Title',
                'description' => 'Updated description',
                'status'      => 2,
                'priority'    => 1,
                'deadline'    => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
            ],
        ]);

        $this->assertDatabaseHas('tasks', [
            'id'          => $task->id,
            'title'       => 'Updated Title',
            'description' => 'Updated description',
            'status'      => 2,
            'priority'    => 1,
            'deadline'    => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
        ]);
    }

    public function testDeleteTask()
    {
        $task = Task::factory()->create([
            'title'       => 'Old Title',
            'description' => 'Old description',
            'status'      => 2,
            'priority'    => 1,
            'deadline'    => Carbon::now()->addDays(1),
        ]);

        $response = $this->deleteJson('/api/tasks/' . $task->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
