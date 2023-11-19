<?php

namespace App\Http\Controllers\Api\Tasks;

use App\Actions\Files\UploadFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\CreateTaskFileRequest;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Tasks\Task;
use Illuminate\Http\Request;

class TaskFileController extends Controller
{
    /**
     * Create task file
     *
     * @param CreateTaskRequest $request
     */
    public function store(CreateTaskFileRequest $request, Task $task)
    {
        UploadFileAction::execute($task, $request->file);

        // Auto include files
        $includes = $request->get('includes');
        $includes = $includes ? explode(',', $includes) : [];
        $includes[] = 'files';
        request()->merge(['includes' => join(',', $includes)]);

        return new TaskResource($task);
    }
}
