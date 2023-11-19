<?php

namespace App\Models\Tasks;

use App\Models\Files\File;
use App\Models\Tags\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'order',
        'due_at',
        'completed_at',
        'archived_at',
        'created_by'
    ];

    /**
     * Scope a query to only include owned tasks.
     */
    public function scopeOwned(Builder $query): void
    {
        if ($user = auth()->user()) {
            $query->where('created_by', $user->id);
        }
    }
    
    /**
     * Get all of the tags for the task.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'model', 'model_has_tags');
    }

    /**
     * Get all of the files for the task.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
