<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method to verify it retrieves tasks for an authenticated user.
     */
    public function test_index_retrieves_tasks_for_authenticated_user()
    {
        // Create a user and authenticate
        $user = User::factory()->create();

        // Create tasks associated with this user
        Task::factory()->count(3)->create(['user_id' => $user->id]);

        // Act as the authenticated user
        $this->actingAs($user);

        // Perform a GET request to the task index route
        $response = $this->get(route('tasks.index'));

        // Assert that the view has all tasks for the authenticated user
        $response->assertStatus(200);
        $response->assertViewHas('tasks', $user->tasks);
    }

    /**
     * Test store method to ensure tasks can be created.
     */
    public function test_store_creates_task_for_authenticated_user()
    {
        // Create a user and authenticate
        $user = User::factory()->create();

        // Act as authenticated user
        $this->actingAs($user);

        // Define task data
        $taskData = [
            'title' => 'New Task',
            'description' => 'New Task Description',
        ];

        // Perform POST request to store a new task
        $response = $this->post(route('tasks.store'), $taskData);

        // Assert the task is stored
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', array_merge($taskData, ['user_id' => $user->id]));
    }

    /**
     * Test update method to ensure a task is updated correctly.
     */
    public function test_update_modifies_task_for_authenticated_user()
    {
        // Create a user and a task
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'completed' => false]);

        // Act as authenticated user
        $this->actingAs($user);

        // Perform PATCH request to update task completion status
        $response = $this->patch(route('tasks.update', $task), ['completed' => true]);

        // Assert the task is updated
        $response->assertRedirect(route('tasks.index'));
        $this->assertTrue(Task::find($task->id)->completed);
    }

    /**
     * Test destroy method to ensure a task is deleted.
     */
    public function test_destroy_removes_task_for_authenticated_user()
    {
        // Create a user and a task
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Act as the authenticated user
        $this->actingAs($user);

        // Perform DELETE request to remove the task
        $response = $this->delete(route('tasks.destroy', $task));

        // Assert the task is deleted
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}