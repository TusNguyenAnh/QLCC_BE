<?php

namespace App\Services\TaskService;

use App\Enums\Constant;
use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Repositories\ExpenseRepository\IExpenseRepository;
use App\Repositories\OrgBuildingRepository\IOrgBuildingRepository;
use App\Repositories\OrgUserRepository\IOrgUserRepository;
use App\Repositories\RevenueRepository\IRevenueRepository;
use App\Repositories\TaskRepository\ITaskHistoryRepository;
use App\Repositories\TaskRepository\ITaskRepository;
use App\Repositories\TaskRepository\ITaskTypeRepository;
use App\Repositories\WorkflowRepository\IWorkflowStepApproverRepository;
use App\Repositories\WorkflowRepository\IWorkflowStepRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskService implements ITaskService
{
    private ITaskRepository $taskRepository;
    private ITaskTypeRepository $taskTypeRepository;
    private IWorkflowStepRepository $workflowStepRepository;
    private IWorkflowStepApproverRepository $workflowStepApproverRepository;
    private IOrgBuildingRepository $orgBuildingRepository;
    private IOrgUserRepository $orgUserRepository;
    private ITaskHistoryRepository $taskHistoryRepository;
    private IExpenseRepository $expenseRepository;
    private IRevenueRepository $revenueRepository;


    public function __construct(ITaskRepository                 $taskRepository,
                                ITaskTypeRepository             $taskTypeRepository,
                                IWorkflowStepRepository         $workflowStepRepository,
                                IOrgBuildingRepository          $orgBuildingRepository,
                                ITaskHistoryRepository          $taskHistoryRepository,
                                IWorkflowStepApproverRepository $workflowStepApproverRepository,
                                IOrgUserRepository              $orgUserRepository,
                                IExpenseRepository              $expenseRepository,
                                IRevenueRepository              $revenueRepository
    )
    {
        $this->taskRepository = $taskRepository;
        $this->taskTypeRepository = $taskTypeRepository;
        $this->workflowStepRepository = $workflowStepRepository;
        $this->orgBuildingRepository = $orgBuildingRepository;
        $this->orgUserRepository = $orgUserRepository;
        $this->taskHistoryRepository = $taskHistoryRepository;
        $this->workflowStepApproverRepository = $workflowStepApproverRepository;
        $this->expenseRepository = $expenseRepository;
        $this->revenueRepository = $revenueRepository;
    }

    public function add(array $data)
    {
        DB::beginTransaction();
        try {

            // Lay ra wf cua task
            $wfId = $this->taskTypeRepository->findById($data['tasktype_id'])->workflow_id;
            // lay ra cac buoc xet duyet cua task
            $wfStep = $this->workflowStepRepository->findByWorkflowId($wfId);
            // lay ra role xet duyet o tung buoc
            $wfStepAndApprover = collect($wfStep)->map(function ($item) {
                $item['approver'] = $this->workflowStepApproverRepository->findByWfStepId($item['id']);
                return $item;
            });

            // lay ra cac cap xet duyet cu the de resolve cho workflow step va tao map: key = level, value = org_id
            $orgs = $this->orgBuildingRepository->findByBuildingId($data['building_id'])->pluck('org_id', 'level');

            // resole org_id cu the se xet duyet task
            $wfStepResolve = collect($wfStepAndApprover)->map(function ($item) use ($orgs) {
                $orgId = $orgs[$item['org_level']];
                if ($orgId) {
                    $item['org_id'] = $orgId;
                    $item['user_id'] = $this->orgUserRepository->findByOrgId($orgId, $item['approver']);
                }
                return $item;
            });

            $task = array_merge($data, [
                'current_step' => $wfStepResolve[0]->step_order,
                'current_org_id' => $wfStepResolve[0]->org_id,
                'status' => Constant::PENDING->value,
            ]);

            $newTask = $this->taskRepository->store($task);

            $dataTaskHistory = [];
            foreach ($wfStepResolve as $step) {
                foreach ($step['user_id'] as $userId) {
                    $dataTaskHistory[] = [
                        'id' => (string)Str::uuid(),
                        'task_id' => $newTask->id,
                        'approver_id' => $userId,
                        'org_id' => $step['org_id'],
                        'step_order' => $step['step_order'],
                        'action' => Constant::PENDING->value,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            $this->taskHistoryRepository->store($dataTaskHistory);
            DB::commit();
            return $newTask;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new AppException(ErrorCode::TASK_INFO_INVALID);
        }
    }

    public function findByOrgId(array $filters, string $orgId, int $taskStatus, $perPage)
    {
        $taskStatus = ($taskStatus == 2) ? Constant::PENDING->value : Constant::REJECT->value;
        $approverId = jwt_claim('sub');
        return $this->taskRepository->findByOrgId($filters, $orgId, $taskStatus, $perPage, $approverId);
    }

    public function findWfByTaskId(string $taskId)
    {
        return $this->taskHistoryRepository->findByTaskId($taskId);
    }

    public function taskActionSummary(string $orgId)
    {
        $approverId = jwt_claim('sub');
        return $this->taskHistoryRepository->taskActionSummary($orgId, $approverId);
    }

    public function approveTask(array $data, string $id)
    {
        DB::beginTransaction();
        try {
            $task = $this->taskRepository->findById($id);
            if (!$task) {
                throw new AppException(ErrorCode::NOT_FOUND);
            }
            $currentOrgId = $task->current_org_id;

            $this->taskHistoryRepository->update($data, $task->id, $currentOrgId, $data['approver_id']);

            $isApproved = $this->taskHistoryRepository->checkAllApprovedInStep($task->id, $task->current_step, Constant::APPROVED->value);
            if (!$isApproved) {
                $nextStep = $task->current_step + 1;
                $nextStepDetail = $this->taskHistoryRepository->findByTaskIdAndStep($id, $nextStep);
                if ($nextStepDetail) {
                    $taskUpdate = [
                        'current_step' => $nextStepDetail->step_order,
                        'current_org_id' => $nextStepDetail->org_id,
                        'status' => Constant::PENDING->value,
                    ];
                } else {
                    $taskUpdate = [
                        'status' => Constant::APPROVED->value,
                    ];

                    // neu ko phai thu/chi thi dung
                    //sau làm xét duyệt đề xuất cư dân thì cần phân biệt bằng 1 cờ gì đó ở đây

                    $isTaskExpense = count($this->expenseRepository->findByTaskId($task->id)) > 0;
                    $isTaskRevenue = count($this->revenueRepository->findByTaskId($task->id)) > 0;

                    //sẽ duyệt chi đổi approved trong expense thành 1
                    if ($isTaskExpense) {
                        $this->expenseRepository->approveExpense([$task->id], $data["approver_id"]);
                    }

                    //sẽ duyệt thu đổi approved trong expense thành 1
                    if ($isTaskRevenue) {
                        $this->revenueRepository->approveRevenue([$task->id], $data["approver_id"]);
                    }
                }


                $this->taskRepository->update($taskUpdate, $task->id);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function rejectTask(array $data, string $id)
    {
        DB::beginTransaction();
        try {
            $task = $this->taskRepository->findById($id);
            if (!$task) {
                throw new AppException(ErrorCode::NOT_FOUND);
            }
            $currentOrgId = $task->current_org_id;

            $this->taskHistoryRepository->updateTaskForRejectStep(['action' => Constant::UNFINISHED->value], $task->id, $task->current_step);
            $this->taskHistoryRepository->update($data, $task->id, $currentOrgId, $data['approver_id']);
            $this->taskRepository->update(['status' => Constant::REJECT->value], $id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function filterTaskApproved(string $orgId, string $taskStatus, array $filters, $perPage)
    {
        $approverId = jwt_claim('sub');
        return $this->taskHistoryRepository->filterTaskApproved($orgId, $approverId, $taskStatus, $filters, $perPage);
    }

    public function findByCreator(array $filters, string $taskStatus, $perPage, $creator)
    {
        return $this->taskRepository->findByCreator($filters, $taskStatus, $perPage, $creator);
    }
}
