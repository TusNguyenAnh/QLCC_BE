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

    public function findByOrgId(string $orgId, string $taskStatus)
    {
        return Task::join('task_type', 'task.tasktype_id', '=', 'task_type.id')
            ->join('priority', 'priority.id', '=', 'task_type.priority_id')
            ->join('organization', 'organization.id', '=', 'task.current_org_id')
            ->join('users', 'users.id', '=', 'task.user_id')
            ->join('residents', 'residents.id', '=', 'users.res_id')
            ->join('apt_res', 'apt_res.resident_id', '=', 'residents.id')
            ->join('apartments', 'apt_res.apt_id', '=', 'apartments.id')
            ->join('buildings', 'apartments.building_id', '=', 'buildings.id')
            ->where('task.current_org_id', $orgId)
            ->where('task.status', $taskStatus)
            ->select('task.*', 'task_type.type_name',
                'priority.priority_name', 'users.username',
                'apartments.apt_number', 'organization.level', 'buildings.building_name')
            ->orderBy('task.created_at', 'DESC')
            ->get();
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
