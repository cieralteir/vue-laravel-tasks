<?php

namespace App\Actions\Tasks;

use App\Actions\Tags\AttachTagsByNameAction;
use App\Models\Tasks\Task;

class UpdateTaskAction
{
    /**
     * Execute action
     *
     * @param Task $task
     * @param array $data
     */
    public static function execute(Task $task, array $data)
    {
        $task->update($data);

        // Attach tags
        if (isset($data['tags'])) {
            AttachTagsByNameAction::execute($task, $data['tags']);
        }

        return $task->fresh();
    }
}
