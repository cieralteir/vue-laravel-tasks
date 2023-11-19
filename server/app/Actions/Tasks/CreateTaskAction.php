<?php

namespace App\Actions\Tasks;

use App\Actions\Files\UploadFileAction;
use App\Actions\Tags\AttachTagsByNameAction;
use App\Models\Tasks\Task;

class CreateTaskAction
{
    /**
     * Execute action
     *
     * @param array $data
     */
    public static function execute(array $data)
    {
        $task = Task::create(array_merge($data, [
            'created_by' => auth()->user()->id,
        ]));

        // Attach tags
        if (isset($data['tags'])) {
            AttachTagsByNameAction::execute($task, $data['tags']);
        }

        // Attach files
        if (isset($data['files'])) {
            foreach ($data['files'] as $file) {
                UploadFileAction::execute($task, $file);
            }
        }

        return $task->fresh();
    }
}
