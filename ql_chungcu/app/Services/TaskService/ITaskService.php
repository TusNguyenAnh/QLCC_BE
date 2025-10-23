<?php

namespace App\Services\TaskService;

use App\Models\Task;

interface ITaskService
{
    public function add(array $data);

    public function findByOrgId(string $orgId, int $taskStatus);

    public function findWfByTaskId(string $taskId);

    public function taskActionSummary(string $orgId);

    public function approveTask(array $data, string $id);

    public function rejectTask(array $data, string $id);
}
