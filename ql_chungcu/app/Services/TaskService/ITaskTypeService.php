<?php

namespace App\Services\TaskService;

use App\Models\TaskType;

interface ITaskTypeService
{
    public function show($perPage, $complexId);
    public function add(array $data): TaskType;
    public function update(string $id, array $data): ?TaskType;
}
