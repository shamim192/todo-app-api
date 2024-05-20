<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *  
     * @group Task Management
     * @response 200 {
     *   "success": true,
     *   "message": "Tasks retrieved successfully.",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Task title",
     *       "description": "Task description",
     *       "is_completed": false,
     *       "created_at": "2023-01-01T00:00:00.000000Z",
     *       "updated_at": "2023-01-01T00:00:00.000000Z"
     *     }
     *   ]
     * }

     */
    public function index()
    {
        $data = Task::all();

        return response()->json([
            'success' => true,
            'message' => 'Tasks retrieved successfully.',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @group Task Management
     * @bodyParam title string required The title of the task. Example: Buy groceries
     * @bodyParam description string The description of the task. Example: Milk, Bread, Cheese
     * @bodyParam is_completed boolean The completion status of the task. Example: false
     * @response 201 {
     *   "success": true,
     *   "message": "Task created successfully.",
     *   "data": {
     *     "id": 1,
     *     "title": "Buy groceries",
     *     "description": "Milk, Bread, Cheese",
     *     "is_completed": false,
     *     "created_at": "2023-01-01T00:00:00.000000Z",
     *     "updated_at": "2023-01-01T00:00:00.000000Z"
     *   }
     * }
     * @response 422 {
     *   "success": false,
     *   "message": "Validation errors",
     *   "data": {
     *     "title": [
     *       "The title field is required."
     *     ]
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'boolean|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], 422);
        }

        $data = Task::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'data' => $data,
        ], 201);
    }

    /**
     * Display the specified resource.
     * 
     * @group Task Management
     * @urlParam id integer required The ID of the task. Example: 1
     * @response 200 {
     *   "success": true,
     *   "message": "Task retrieved successfully.",
     *   "data": {
     *     "id": 1,
     *     "title": "Buy groceries",
     *     "description": "Milk, Bread, Cheese",
     *     "is_completed": false,
     *     "created_at": "2023-01-01T00:00:00.000000Z",
     *     "updated_at": "2023-01-01T00:00:00.000000Z"
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Task not found",
     *   "data": null
     * }
     */
    public function show(string $id)
    {
        $data = Task::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task retrieved successfully.',
            'data' => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @group Task Management
     * @urlParam id integer required The ID of the task. Example: 1
     * @bodyParam title string required The title of the task. Example: Buy groceries
     * @bodyParam description string The description of the task. Example: Milk, Bread, Cheese
     * @bodyParam is_completed boolean The completion status of the task. Example: false
     * @response 200 {
     *   "success": true,
     *   "message": "Task updated successfully.",
     *   "data": {
     *     "id": 1,
     *     "title": "Buy groceries
     *     "description": "Milk, Bread, Cheese",
     *     "is_completed": false,
     *     "created_at": "2023-01-01T00:00:00.000000Z",
     *     "updated_at": "2023-01-01T00:00:00.000000Z"
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Task not found",
     *   "data": null
     * }
     * @response 422 {
     *   "success": false,
     *   "message": "Validation errors",
     *   "data": {
     *     "title": [
     *       "The title field is required."
     *     ]
     *   }
     * }
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'string|nullable',
            'is_completed' => 'boolean|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], 422);
        }

        $data = Task::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found',
                'data' => null,
            ], 404);
        }

        $data->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data' => $data,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @group Task Management
     * @urlParam id integer required The ID of the task. Example: 1
     * @response 200 {
     *   "success": true,
     *   "message": "Task deleted successfully.",
     *   "data": null
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Task not found",
     *   "data": null
     * }
     */
    public function destroy(string $id)
    {
        $data = Task::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found',
                'data' => null,
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
            'data' => null,
        ], 200);
    }
}
