<?php

namespace App\Policies\Tasks;

use App\Models\Tasks\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can manage the model.
     */
    public function manage(User $user, Task $task): bool
    {
        return $user->id == $task->created_by;
    }
}
