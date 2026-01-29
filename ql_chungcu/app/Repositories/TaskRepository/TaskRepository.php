<?php

namespace App\Repositories\TaskRepository;

use App\Models\Task;

class TaskRepository implements ITaskRepository
{

    public function store(array $data)
    {
        $task = Task::create($data)->fresh();
        return $task;
    }

    public function findByOrgId(array $filters, string $orgId, string $taskStatus, $perPage, $approverId)
    {
        $query = Task::join('task_type', 'task.tasktype_id', '=', 'task_type.id')
            ->join('priority', 'priority.id', '=', 'task_type.priority_id')
            ->join('organization', 'organization.id', '=', 'task.current_org_id')
            ->join('users', 'users.id', '=', 'task.creator')
            ->join('residents', 'residents.id', '=', 'users.res_id') // can xem xet voi truong hop ban quan ly thi co staff id
            ->join('apt_res', 'apt_res.resident_id', '=', 'residents.id')
            ->join('apartments', 'apt_res.apt_id', '=', 'apartments.id')
            ->join('buildings', 'apartments.building_id', '=', 'buildings.id')
            ->join('task_history', 'task_history.task_id', '=', 'task.id')
            ->where('task.current_org_id', $orgId)
            ->whereColumn('task.current_step', 'task_history.step_order')
            ->where('task_history.action', $taskStatus)
            ->where('task_history.approver_id', $approverId)
            ->select('task.*', 'task_type.type_name',
                'priority.priority_name', 'users.username',
                'residents.phone_number', 'residents.fullname',
                'apartments.apt_number', 'organization.level', 'buildings.building_name');

        //Điều kiện lọc khi có request
        $query->when(
            isset($filters['priority_id']) && is_array($filters['priority_id']) && count($filters['priority_id']) > 0,
            fn($q) => $q->whereIn('priority.id', $filters['priority_id'])
        );

        $query->when(
            isset($filters['taskType_id']) && is_array($filters['taskType_id']) && count($filters['taskType_id']) > 0,
            fn($q) => $q->whereIn('task.tasktype_id', $filters['taskType_id'])
        );

        // Lọc từ thời gian từ chối bắt đầu
        $query->when($filters['time_approved_start'] ?? null,
            fn($q, $v) => $q->whereDate('task.updated_at', '>=', $v)
        );

        // Lọc đến thời gian từ chối kết thúc
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
        $query->orderBy('task.created_at', $order);

        return $query->paginate($perPage);
    }

    public function findByCreator(array $filters, string $taskStatus, $perPage, $creator)
    {
        $query = Task::join('task_type', 'task.tasktype_id', '=', 'task_type.id')
            ->join('priority', 'priority.id', '=', 'task_type.priority_id')
            ->join('users', 'users.id', '=', 'task.creator')
            ->join('residents', 'residents.id', '=', 'users.res_id') // can xem xet voi truong hop ban quan ly thi co staff id
            ->join('apt_res', 'apt_res.resident_id', '=', 'residents.id')
            ->join('apartments', 'apt_res.apt_id', '=', 'apartments.id')
            ->join('buildings', 'apartments.building_id', '=', 'buildings.id')
            ->where('task.creator', $creator)
            ->where('task.status', $taskStatus)
            ->where('task.category','task')
            ->select('task.*', 'task_type.type_name',
                'priority.priority_name', 'users.username',
                'residents.phone_number', 'residents.fullname',
                'apartments.apt_number', 'buildings.building_name');

        //Điều kiện lọc khi có request
        $query->when(
            isset($filters['priority_id']) && is_array($filters['priority_id']) && count($filters['priority_id']) > 0,
            fn($q) => $q->whereIn('priority.id', $filters['priority_id'])
        );

        $query->when(
            isset($filters['taskType_id']) && is_array($filters['taskType_id']) && count($filters['taskType_id']) > 0,
            fn($q) => $q->whereIn('task.tasktype_id', $filters['taskType_id'])
        );

        // Lọc từ thời gian từ chối bắt đầu
        $query->when($filters['time_approved_start'] ?? null,
            fn($q, $v) => $q->whereDate('task.updated_at', '>=', $v)
        );

        // Lọc đến thời gian từ chối kết thúc
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
        $query->orderBy('task.created_at', $order);

        return $query->paginate($perPage);
    }

    public function update(array $data, string $id)
    {
        $task = Task::where('id', $id)->first();
        if (!$task) return null;

        $task->update($data);
        return $task->fresh();
    }

    public function findById(string $taskId)
    {
        return Task::where('id', $taskId)->first();
    }
}
