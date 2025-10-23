<?php

namespace App\Services\TaskService;

use App\Models\TaskType;
use App\Repositories\TaskRepository\ITaskTypeRepository;
use Illuminate\Support\Arr;

class TaskTypeService implements ITaskTypeService
{
    private ITaskTypeRepository $taskTypeRepository;

    public function __construct(ITaskTypeRepository $taskTypeRepository)
    {
        $this->taskTypeRepository = $taskTypeRepository;
    }

    public function add(array $data): TaskType
    {
        $taskType = $this->taskTypeRepository->store($data);
        return $taskType;
    }

    public function update(string $id, array $data): ?TaskType
    {
        // TODO: Implement update() method.
    }

    public function show($perPage, $complexId)
    {
        return $this->taskTypeRepository->show($perPage, $complexId);
    }
}
