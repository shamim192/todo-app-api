# Laravel To-Do List API

This is a RESTful API for a To-Do List application built with Laravel.

## Features

- Create a new task
- Retrieve a list of all tasks
- Retrieve a specific task by ID
- Update the title, description, or completion status of a task
- Delete a task by ID

## Requirements

- PHP >= 8.0
- Composer
- MySQL

## Installation

1. **Clone the repository**:

    ```sh
    git clone https://github.com/shamim192/todo-app-api.git
    cd laravel-todo-api
    ```

2. **Install dependencies**:

    ```sh
    composer install
    ```

3. **Copy the example environment file and configure environment settings**:

    ```sh
    cp .env.example .env
    ```

    Open the `.env` file and set your database configurations:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

4. **Generate an application key**:

    ```sh
    php artisan key:generate
    ```

5. **Run the database migrations**:

    ```sh
    php artisan migrate
    ```

6. **Run the application**:

    ```sh
    php artisan serve
    ```

    The application will be available at `http://localhost:8000`.

## API Endpoints

### Create Task

- **URL**: `/api/tasks`
- **Method**: `POST`
- **Body Parameters**:
  - `title` (string, required): The title of the task.
  - `description` (string, optional): The description of the task.
  - `is_completed` (boolean, optional): The completion status of the task.

- **Response**:
  - `201 Created` on success

### Read Tasks

- **URL**: `/api/tasks`
- **Method**: `GET`

- **Response**:
  - `200 OK` on success

### Read Single Task

- **URL**: `/api/tasks/{id}`
- **Method**: `GET`

- **Response**:
  - `200 OK` on success
  - `404 Not Found` if the task does not exist

### Update Task

- **URL**: `/api/tasks/{id}`
- **Method**: `PUT`
- **Body Parameters**:
  - `title` (string, required): The title of the task.
  - `description` (string, optional): The description of the task.
  - `is_completed` (boolean, optional): The completion status of the task.

- **Response**:
  - `200 OK` on success
  - `404 Not Found` if the task does not exist

### Delete Task

- **URL**: `/api/tasks/{id}`
- **Method**: `DELETE`

- **Response**:
  - `200 OK` on success
  - `404 Not Found` if the task does not exist

## Testing

### Unit Tests

To run the unit tests, execute the following command:

```sh
php artisan test
```
### API Documentation
  
This project uses Laravel Scribe for API documentation. To generate the documentation, run:

```sh
php artisan scribe:generate
```
You can view the generated documentation by navigating to /docs in your browser when the application is running.

### Unit Tests for API Endpoints

Here's a complete example of the unit tests for the API endpoints using Laravel's testing tools. These tests cover various scenarios including edge cases:

```php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_a_task()
    {
        $data = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'is_completed' => false
        ];

        $this->postJson('/api/tasks', $data)
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Task created successfully.',
                'data' => [
                    'title' => 'Test Task',
                    'description' => 'Test Description',
                    'is_completed' => false
                ]
            ]);
    }

    /** @test */
    public function retrieve_all_tasks()
    {
        Task::factory()->count(5)->create();

        $this->getJson('/api/tasks')
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'is_completed',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    /** @test */
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

    /** @test */
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

    /** @test */
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
                'data' => [
                    'id' => $task->id,
                    'title' => 'Updated Task',
                    'description' => 'Updated Description',
                    'is_completed' => true,
                ]
            ]);
    }

    /** @test */
    public function returns_404_if_updating_non_existing_task()
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

    /** @test */
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
    }

    /** @test */
    public function returns_404_if_deleting_non_existing_task()
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

