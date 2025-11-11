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

    public function filterTaskApproved(string $orgId, string $taskStatus, array $filters,$perPage)
    {
        $query = TaskHistory::join('task', 'task_history.task_id', '=', 'task.id')
            ->join('task_type', 'task.tasktype_id', '=', 'task_type.id')
            ->join('priority', 'priority.id', '=', 'task_type.priority_id')
            ->join('organization', 'organization.id', '=', 'task.current_org_id')
            ->join('users', 'users.id', '=', 'task.user_id')
            ->join('residents', 'residents.id', '=', 'users.res_id')
            ->join('apt_res', 'apt_res.resident_id', '=', 'residents.id')
            ->join('apartments', 'apt_res.apt_id', '=', 'apartments.id')
            ->join('buildings', 'apartments.building_id', '=', 'buildings.id')
            ->where('task_history.org_id', $orgId)
            ->where('task_history.action', $taskStatus)
            ->where('task.status', $taskStatus)
            ->select('task.*', 'task_type.type_name',
                'priority.priority_name', 'priority.id as priority_id',
                'users.username','residents.phone_number',
                'apartments.apt_number', 'organization.level', 'buildings.building_name');

        //Điều kiện lọc khi có request
        $query->when(!empty($filters['priority_id']),
            fn($q) => $q->whereIn('priority.id', $filters['priority_id'])
        );

        $query->when(!empty($filters['taskType_id']),
            fn($q) => $q->whereIn('task.tasktype_id', $filters['taskType_id'])
        );

        // Lọc từ thời gian duyệt bắt đầu
        $query->when($filters['time_approved_start'] ?? null,
            fn($q, $v) => $q->whereDate('task.updated_at', '>=', $v)
        );

        // Lọc đến thời gian duyệt kết thúc
        $query->when($filters['time_approved_end'] ?? null,
            fn($q, $v) => $q->whereDate('task.updated_at', '<=', $v)
        );

        $query->when($filters['time_request_start'] ?? null,
            fn($q, $v) => $q->whereDate('task.created_at', '>=', $v)
        );

        $query->when($filters['time_request_end'] ?? null,
            fn($q, $v) => $q->whereDate('task.created_at', '<=', $v)
        );

        // Order linh hoạt
        $order = strtolower($filters['order'] ?? 'desc');
        $order = in_array($order, ['asc', 'desc']) ? $order : 'desc';
        $query->orderBy('task.updated_at', $order);

        return $query->paginate($perPage);
    }
}
