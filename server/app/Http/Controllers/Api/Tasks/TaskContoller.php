<?php

namespace App\Http\Controllers\Api\Tasks;

use App\Actions\Tasks\CreateTaskAction;
use App\Actions\Tasks\DeleteTaskAction;
use App\Actions\Tasks\ListTasksAction;
use App\Actions\Tasks\UpdateTaskAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\CreateTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Tasks\Task;
use Illuminate\Http\Request;

class TaskContoller extends Controller
{
    /**
     * Display tasks
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        // Filters
        $filters = [
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'priority' => $request->get('priority'),
            'due_at_start' => $request->get('due_at_start'),
            'due_at_end' => $request->get('due_at_end'),
            'completed_at_start' => $request->get('completed_at_start'),
            'completed_at_end' => $request->get('completed_at_end'),
            'archived_at_start' => $request->get('archived_at_start'),
            'archived_at_end' => $request->get('archived_at_end'),
        ];
        // Sort
        $orderBy = $request->get('order_by', 'id');
        $sortBy = $request->get('sort_by', 'asc');
        // Page
        $page = $request->input('page', 1);
        $perPage = $request->get('per_page', 100);

        $data = ListTasksAction::execute($filters, $orderBy, $sortBy, $page, $perPage);

        return TaskResource::collection($data);
    }

    /**
     * Create task
     *
     * @param CreateTaskRequest $request
     */
    public function store(CreateTaskRequest $request)
    {
        $data = CreateTaskAction::execute($request->all());

        return new TaskResource($data);
    }

    /**
     * Display task
     *
     * @param Task $task
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update task
     *
     * @param UpdateTaskRequest $request
     * @param Task $task
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = UpdateTaskAction::execute($task, $request->all());

        return new TaskResource($data);
    }

    /**
     * Delete task
     *
     * @param Task $task
     */
    public function destroy(Task $task)
    {
        DeleteTaskAction::execute($task);

        return response()->json([], 204);
    }

    /**
     * Complete task
     *
     * @param Task $task
     */
    public function complete(Task $task)
    {
        $data = UpdateTaskAction::execute($task, [
            'completed_at' => now(),
        ]);

        return new TaskResource($data);
    }

    /**
     * Mark as todo task
     *
     * @param Task $task
     */
    public function uncomplete(Task $task)
    {
        $data = UpdateTaskAction::execute($task, [
            'completed_at' => null,
        ]);

        return new TaskResource($data);
    }

    /**
     * Archive task
     *
     * @param Task $task
     */
    public function archive(Task $task)
    {
        $data = UpdateTaskAction::execute($task, [
            'archived_at' => now(),
        ]);

        return new TaskResource($data);
    }

    /**
     * Restore task
     *
     * @param Task $task
     */
    public function restore(Task $task)
    {
        $data = UpdateTaskAction::execute($task, [
            'archived_at' => null,
        ]);

        return new TaskResource($data);
    }
}
