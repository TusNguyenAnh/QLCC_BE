<?php

namespace App\Repositories\TaskRepository;

use App\Models\TaskHistory;

class TaskHistoryRepository implements ITaskHistoryRepository
{

    public function store(array $data)
    {
        $taskHistory = TaskHistory::insert($data);
        return $taskHistory;
    }

    public function findByTaskId(string $taskId)
    {
        return TaskHistory::join('task', 'task.id', '=', 'task_history.task_id')
            ->join('organization', 'organization.id', '=', 'task_history.org_id')
            ->join('task_type', 'task_type.id', '=', 'task.tasktype_id')
            ->join('workflow', 'task_type.workflow_id', '=', 'workflow.id')
            ->where('task_id', $taskId)
            ->select('task_history.*', 'organization.org_name', 'organization.level', 'workflow.workflow_name')
            ->orderBy('step_order', 'ASC')
            ->get();
    }

    public function taskActionSummary(string $orgId)
    {
        return TaskHistory::selectRaw('action, COUNT(*) as total')
            ->where('org_id', $orgId)
            ->groupBy('action')
            ->unionAll(
                TaskHistory::selectRaw("'ALL' as action, COUNT(*) as total")
                    ->where('org_id', $orgId)
            )
            ->get();
    }

    public function findByTaskIdAndStep(string $taskId, int $step)
    {
        return TaskHistory::where([
            ['task_id', '=', $taskId],
            ['step_order', '=', $step]
        ])->first();
    }

    public function update(array $data, string $taskId, string $orgId)
    {
        $taskHistory = TaskHistory::where([
                ['task_id', '=', $taskId],
                ['org_id', '=', $orgId]
            ]
        )->first();
        if (!$taskHistory) return null;

        $taskHistory->update($data);
        return $taskHistory->fresh();
    }

    public function updateTaskForRejectStep(array $data, string $taskId, string $stepOrder)
    {
        $taskHistory = TaskHistory::where([
                ['task_id', '=', $taskId],
                ['step_order', '>', $stepOrder]
            ]
        )->update($data);

        return $taskHistory;
    }
}
