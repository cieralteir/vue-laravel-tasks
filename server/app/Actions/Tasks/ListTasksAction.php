<?php

namespace App\Actions\Tasks;

use App\Models\Tasks\Task;

class ListTasksAction
{
    /**
     * Execute action
     *
     * @param array $filters
     * @param string $orderBy
     * @param string $sortBy
     * @param integer $page
     * @param integer $perPage
     */
    public static function execute(array $filters, string $orderBy = 'id', string $sortBy = 'asc', int $page = 1, int $perPage = 100)
    {
        $tasks = Task::query();
        $tasks->owned();

        $_filters = [];
        foreach ($filters as $key => $value) {
            if (is_null($value)) continue;
            switch ($key) {
                case 'title':
                case 'description':
                    $_filters[] = [$key, "%{$value}%", 'like'];
                    break;
                case 'due_at_start':
                case 'completed_at_start':
                case 'archived_at_start':
                    $_filters[] = [str_replace('_start', '', $key), $value, '>='];
                    break;
                case 'due_at_end':
                case 'completed_at_end':
                case 'archived_at_end':
                        $_filters[] = [str_replace('_end', '', $key), $value, '<='];
                        break;
                default:
                    $_filters[] = [$key, $value, '='];
            }
        }
        foreach ($_filters as $_filter) {
            $tasks->where($_filter[0], $_filter[2], $_filter[1]);
        }

        // if (isset($filters['title'])) {
        //     $tasks->where('title', 'like', "%{$filters['title']}%");
        // }

        // if (isset($filters['description'])) {
        //     $tasks->where('description', 'like', "%{$filters['description']}%");
        // }

        // if (isset($filters['priority'])) {
        //     $tasks->where('priority', $filters['priority']);
        // }

        // if (isset($filters['due_at_start'])) {
        //     $tasks->where('due_at', '>=', $filters['due_at_start']);
        // }
        // if (isset($filters['due_at_end'])) {
        //     $tasks->where('due_at', '<=', $filters['due_at_end']);
        // }

        // if (isset($filters['completed_at_start'])) {
        //     $tasks->where('completed_at', '>=', $filters['completed_at_start']);
        // }
        // if (isset($filters['completed_at_end'])) {
        //     $tasks->where('completed_at', '<=', $filters['completed_at_end']);
        // }

        // if (isset($filters['archived_at_start'])) {
        //     $tasks->where('archived_at', '>=', $filters['archived_at_start']);
        // }
        // if (isset($filters['archived_at_end'])) {
        //     $tasks->where('archived_at', '<=', $filters['archived_at_end']);
        // }

        $tasks->orderBy($orderBy, $sortBy);

        return $tasks->paginate($perPage, ['*'], 'page', $page);
    }
}
