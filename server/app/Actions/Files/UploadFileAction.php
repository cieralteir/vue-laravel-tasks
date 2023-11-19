<?php

namespace App\Actions\Files;

use App\Models\Tasks\Task;
use Illuminate\Database\Eloquent\Model;

class UploadFileAction
{
    /**
     * Execute action
     *
     * @param Model $model
     * @param array $tag_ids
     * @param string $rel_name
     */
    public static function execute(Model $model, $file, $rel_name = 'files')
    {
        $data = [
            'fileable_id' => $model->id,
            'fileable_type' => get_class($model),
            'name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'disk' => 'local',
        ];

        $data['filename'] = $file->store("tasks/{$model->id}");

        $model->{$rel_name}()->create($data);
    }
}
