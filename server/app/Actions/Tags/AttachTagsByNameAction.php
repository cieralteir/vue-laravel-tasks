<?php

namespace App\Actions\Tags;

use App\Models\Tags\Tag;
use App\Models\Tasks\Task;
use Illuminate\Database\Eloquent\Model;

class AttachTagsByNameAction
{
    /**
     * Execute action
     *
     * @param Model $model
     * @param array $tag_names
     * @param string $rel_name
     */
    public static function execute(Model $model, array $tag_names, string $rel_name = 'tags')
    {
        $tag_ids = [];

        foreach ($tag_names as $tag_name) {
            $tag = Tag::firstOrCreate(
                ['name' => $tag_name],
            );
            $tag_ids[] = $tag->id;
        }

        AttachTagsAction::execute($model, $tag_ids, $rel_name);
    }
}
