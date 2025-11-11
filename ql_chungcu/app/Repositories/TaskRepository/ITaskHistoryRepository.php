<?php

namespace App\Repositories\TaskRepository;

interface ITaskHistoryRepository
{
    public function store(array $data);
    public function findByTaskId(string $taskId);
    public function findByTaskIdAndStep(string $taskId, int $step);
    public function taskActionSummary(string $orgId);
    public function update(array $data, string $taskId, string $orgId);
    public function updateTaskForRejectStep(array $data, string $taskId, string $stepOrder);
    public function filterTaskApproved(string $orgId, string $taskStatus, array $filters,$perPage);
}
