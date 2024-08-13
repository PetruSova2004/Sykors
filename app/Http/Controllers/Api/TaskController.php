<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Get all tasks.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tasks = Task::all();
        return response()->json(['tasks' => $tasks]);
    }

    /**
     * Create a new task.
     *
     * @param CreateTaskRequest $request
     * @return JsonResponse
     */
    public function store(CreateTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $task = Task::query()
            ->create([
                'url' => $validated['url'],
                'parameters' => json_encode($validated['parameters']),
                'execution_date' => $validated['execution_date'],
                'status_id' => Task::STATUS_IN_QUEUE,
                'status_description' => 'In_queue',
            ]);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task
        ]);
    }

    /**
     * Update the status of a task.
     *
     * @param UpdateTaskStatusRequest $request
     * @return JsonResponse
     */
    public function updateStatus(UpdateTaskStatusRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $statusId = $request->getStatusId();
        if (!$statusId) {
            return response()->json(['message' => 'Invalid status'], 400);
        }

        $task = Task::find($validated['id']);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->status_id = $statusId;
        $task->status_description = ucfirst($validated['status']);
        $task->exception = null;
        $task->save();

        return response()->json([
            'message' => 'Task status updated successfully',
            'task' => $task
        ]);
    }

}
