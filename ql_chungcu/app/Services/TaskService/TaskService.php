<?php

namespace App\Services\TaskService;

use App\Models\Task;
use App\Repositories\OrgBuildingRepository\IOrgBuildingRepository;
use App\Repositories\TaskRepository\ITaskHistoryRepository;
use App\Repositories\TaskRepository\ITaskRepository;
use App\Repositories\TaskRepository\ITaskTypeRepository;
use App\Repositories\WorkflowRepository\IWorkflowStepRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TaskService implements ITaskService
{
    private ITaskRepository $taskRepository;
    private ITaskTypeRepository $taskTypeRepository;
    private IWorkflowStepRepository $workflowStepRepository;
    private IOrgBuildingRepository $orgBuildingRepository;
    private ITaskHistoryRepository $taskHistoryRepository;


    public function __construct(ITaskRepository         $taskRepository,
                                ITaskTypeRepository     $taskTypeRepository,
                                IWorkflowStepRepository $workflowStepRepository,
                                IOrgBuildingRepository  $orgBuildingRepository,
                                ITaskHistoryRepository  $taskHistoryRepository
    )
    {
        $this->taskRepository = $taskRepository;
        $this->taskTypeRepository = $taskTypeRepository;
        $this->workflowStepRepository = $workflowStepRepository;
        $this->orgBuildingRepository = $orgBuildingRepository;
        $this->taskHistoryRepository = $taskHistoryRepository;
    }

    public function add(array $data)
    {
//        $task = $this->taskRepository->store($data);
        // Lay ra wf cua task
        $wfId = $this->taskTypeRepository->findById($data['tasktype_id'])->workflow_id;
        // lay ra cac buoc xet duyet cua task
        $wfStep = $this->workflowStepRepository->findByWorkflowId($wfId);
        // lay ra cac cap xet duyet cu the de resolve cho workflow step va tao map: key = level, value = org_id
        $orgs = $this->orgBuildingRepository->findByBuildingId($data['building_id'])->pluck('org_id', 'level');

        // resole org_id cu the se xet duyet task
        $wfStepResolve = collect($wfStep)->map(function ($item) use ($orgs) {
            $item['org_id'] = $orgs[$item['org_level']] ?? null;
            return $item;
        });

        $task = array_merge($data, [
            'current_step' => $wfStepResolve[0]->step_order,
            'current_org_id' => $wfStepResolve[0]->org_id,
            'status' => 'PENDING',
        ]);
        $newTask = $this->taskRepository->store($task);

        $newTaskHistory = collect($wfStepResolve)->map(function ($step) use ($newTask, $data) {
            $step['id'] = (string)Str::uuid();
            $step['task_id'] = $newTask->id;
//            $step['user_id'] = $data["user_id"];
            $step['action'] = 'PENDING';
            return Arr::except($step, ['org_level', 'workflow_id', 'status', 'description']);
        })->toArray();

        $this->taskHistoryRepository->store($newTaskHistory);

        return $newTaskHistory;
    }

    public function findByOrgId(string $orgId, int $taskStatus)
    {
        $taskStatus = ($taskStatus == 2) ? "PENDING" : "REJECTED";
        return $this->taskRepository->findByOrgId($orgId, $taskStatus);
    }

    public function findWfByTaskId(string $taskId)
    {
        return $this->taskHistoryRepository->findByTaskId($taskId);
    }

    public function taskActionSummary(string $orgId)
    {
        return $this->taskHistoryRepository->taskActionSummary($orgId);
    }

    public function approveTask(array $data, string $id)
    {
        $task = $this->taskRepository->findById($id);
        $currentOrgId = $task->current_org_id;
        $taskHistory = $this->taskHistoryRepository->update($data, $task->id, $currentOrgId);
        $nextStep = $task->current_step + 1;
        $nextStepDetail = $this->taskHistoryRepository->findByTaskIdAndStep($id, $nextStep);
        $taskUpdate = [];
        if ($nextStepDetail) {
            $taskUpdate = array_merge(Arr::except($data, ['user_id']), [
                'current_step' => $nextStepDetail->step_order,
                'current_org_id' => $nextStepDetail->org_id,
                'status' => $nextStepDetail->action,
            ]);
        } else {
            $taskUpdate = [
                'status' => 'APPROVED',
            ];
        }

        return $this->taskRepository->update($taskUpdate, $id);
    }

    public function rejectTask(array $data, string $id)
    {
        $task = $this->taskRepository->findById($id);
        $currentOrgId = $task->current_org_id;

        $taskUpdateForNextStep = $this->taskHistoryRepository->updateTaskForRejectStep(['action' => 'UNFINISHED'], $task->id, $task->current_step);
        $taskHistory = $this->taskHistoryRepository->update($data, $task->id, $currentOrgId);
        return $this->taskRepository->update(['status' => 'REJECTED'], $id);
    }
}
