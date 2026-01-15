<?php

namespace App\Repositories\TaskRepository;

interface ITaskHistoryRepository
{
    public function store(array $data);
    public function findByTaskId(string $taskId);
    public function findByTaskIdAndStep(string $taskId, int $step);
    public function taskActionSummary(string $orgId,string $approverId);
    public function update(array $data, string $taskId, string $orgId,string $approverId);
    public function updateTaskForRejectStep(array $data, string $taskId, string $stepOrder);
    public function filterTaskApproved(string $orgId, string $approverId, string $taskStatus, array $filters, $perPage);
    public function checkAllApprovedInStep(string $taskId, int $step,string $action);
}
