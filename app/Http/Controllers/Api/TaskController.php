<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     title="Task",
 *     description="Task model",
 *     required={"title", "status","priority","deadline"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the task"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the task"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Status of the task.0=>in progress,1=>postponed,2=>done",
 *         enum={"0", "1", "2"}
 *     ),
 *     @OA\Property(
 *         property="priority",
 *         type="string",
 *         description="Priority of the task.0=>low,1=>medium,2=>high",
 *         enum={"0", "1", "2"}
 *      ),
 *     @OA\Property(
 *         property="deadline",
 *         type="string",
 *         format="date",
 *         example="2024-08-19 12:34:56",
 *         description="Task Deadline"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         format="string",
 *         description="Task description"
 *     )
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Info(
     *     title="Task API",
     *     version="1.0.0",
     *     description="API documentation for managing tasks"
     * )
     *
     * @OA\Server(
     *     url="http://localhost:8000/api",
     *     description="Local API server"
     * )
     */

    /**
     * @OA\Post(
     *     path="/tasks",
     *     tags={"Tasks"},
     *     summary="Create a new task",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */

    public function store(StoreTaskRequest $request)
    {
        try {
            $task = Task::create($request->validated());

            return response()->json(TaskResource::make($task), 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="Get all tasks",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 ref="#/components/schemas/Task"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function index()
    {
        try {
            $tasks = Task::all();

            return TaskResource::collection($tasks);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Get a single task by ID",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A single task",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show($task)
    {
        try {
            $task = Task::findOrFail($task);

            return TaskResource::make($task);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     summary="Update an existing task",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function update(StoreTaskRequest $request, $task)
    {
        try {
            $task = Task::findOrFail($task);
            $task->update($request->validated());

            return TaskResource::make($task);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     summary="Delete a task",
     *     description="Delete a specific task by ID.",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task to delete",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description=""
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     ),
     * )
     */
    public function destroy($task)
    {
        try {
            $task = Task::findOrFail($task);
            $task->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
