<?php

namespace App\Http\Resources\Tasks;

use App\Http\Resources\Tags\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $includes = $request->get('includes');
        $includes = $includes ? explode(',', $includes) : [];
        $this->loadMissing($includes);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'order' => $this->order,
            'due_at' => $this->due_at,
            'completed_at' => $this->completed_at,
            'archived_at' => $this->archived_at,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
