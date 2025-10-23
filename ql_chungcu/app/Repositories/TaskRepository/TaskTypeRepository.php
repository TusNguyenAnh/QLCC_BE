<?php

namespace App\Repositories\TaskRepository;

use App\Models\TaskType;

class TaskTypeRepository implements ITaskTypeRepository
{

    public function store(array $data)
    {
        $tt = TaskType::create($data)->fresh();
        return $tt;
    }

    public function update(array $data, string $id)
    {
        // TODO: Implement update() method.
    }

    public function show($perPage, $complexId)
    {
        return TaskType::where('complex_id', $complexId)
            ->paginate($perPage);
    }

    public function findById(string $id)
    {
        return TaskType::where('id', $id)->first();
    }
}
