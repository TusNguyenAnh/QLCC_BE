<?php

namespace App\Services\TaskService;

use App\Http\Requests\TaskRequest\TaskFilterRequest;
use App\Models\Task;

interface ITaskService
{
    public function add(array $data);

    public function findByOrgId(array $filters,string $orgId, int $taskStatus,$perPage);

    public function findWfByTaskId(string $taskId);

    public function taskActionSummary(string $orgId);

    public function approveTask(array $data, string $id);

    public function rejectTask(array $data, string $id);
    public function filterTaskApproved(string $orgId, string $taskStatus, array $filters,$perPage);
}
