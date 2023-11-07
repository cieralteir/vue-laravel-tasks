<?php

namespace App\Actions\Tasks;

use App\Models\Tasks\Task;

class DeleteTaskAction
{
    /**
     * Execute action
     *
     * @param Task $task
     */
    public static function execute(Task $task)
    {
        $task->delete();
    }
}
