<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tasks = Tasks::all();
            return response()->json([
                "success" => true,
                "data" => $tasks
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to fetch tasks",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $task = Tasks::find($id);
            
            if (!$task) {
                return response()->json([
                    "success" => false,
                    "message" => "Task not found"
                ], 404);
            }
            
            return response()->json([
                "success" => true,
                "data" => $task
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to fetch task",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required|integer|exists:users,id",
            "category_id" => "nullable|integer",
            "title" => "required|string|max:255",
            "description" => "nullable|string",
            "status" => "nullable|in:pending,in_progress,completed",
            "priority" => "nullable|in:low,medium,high",
            "due_date" => "nullable|date",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        try {
            $task = Tasks::create($request->all());
            
            return response()->json([
                "success" => true,
                "message" => "Task created successfully",
                "data" => $task
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to create task",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "nullable|integer|exists:users,id",
            "category_id" => "nullable|integer",
            "title" => "nullable|string|max:255",
            "description" => "nullable|string",
            "status" => "nullable|in:pending,in_progress,completed",
            "priority" => "nullable|in:low,medium,high",
            "due_date" => "nullable|date",
            "completed_at" => "nullable|date",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        try {
            $task = Tasks::find($id);
            
            if (!$task) {
                return response()->json([
                    "success" => false,
                    "message" => "Task not found"
                ], 404);
            }
            
            $task->update($request->all());
            
            return response()->json([
                "success" => true,
                "message" => "Task updated successfully",
                "data" => $task
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to update task",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $task = Tasks::find($id);
            
            if (!$task) {
                return response()->json([
                    "success" => false,
                    "message" => "Task not found"
                ], 404);
            }
            
            $task->delete();
            
            return response()->json([
                "success" => true,
                "message" => "Task deleted successfully"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to delete task",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
