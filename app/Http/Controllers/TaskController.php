<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
   /**
     * Display a listing of the resource.
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
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|nullable',
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
