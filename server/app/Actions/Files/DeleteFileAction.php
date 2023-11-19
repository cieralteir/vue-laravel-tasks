<?php

namespace App\Actions\Files;

use App\Models\Files\File;
use App\Models\Tasks\Task;

class DeleteFileAction
{
    /**
     * Execute action
     *
     * @param File $file
     */
    public static function execute(File $file)
    {
        $file->delete();
    }
}
