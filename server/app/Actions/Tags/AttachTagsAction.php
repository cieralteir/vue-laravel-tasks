<?php

namespace App\Actions\Tags;

use App\Models\Tasks\Task;
use Illuminate\Database\Eloquent\Model;

class AttachTagsAction
{
    /**
     * Execute action
     *
     * @param Model $model
     * @param array $tag_ids
     * @param string $rel_name
     */
    public static function execute(Model $model, array $tag_ids, string $rel_name = 'tags')
    {
        $model->{$rel_name}()->sync($tag_ids);
    }
}
