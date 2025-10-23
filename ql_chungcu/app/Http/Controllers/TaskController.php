<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest\TaskRequest;
use App\Http\Requests\TaskRequest\TaskUpdateRequest;
use App\Http\Resources\TaskActionSummaryResource;
use App\Http\Resources\TaskResource;
use App\Responses\APIResponse;
use App\Services\TaskService\ITaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected ITaskService $taskService;

    public function __construct(ITaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(TaskRequest $taskRequest)
    {
        $data = $taskRequest->validated();
        $data["user_id"] = auth()->user()->id;
        $data["res_id"] = auth()->user()->res_id;

        $task = $this->taskService->add($data);
        return APIResponse::success($task);
    }

    public function findByOrgId(int $taskStatus,string $orgId)
    {
        $task = $this->taskService->findByOrgId($orgId,$taskStatus);
        return APIResponse::success($task);
    }

    public function findWfByTaskId(string $taskId)
    {
        $taskWf = $this->taskService->findWfByTaskId($taskId);
        return APIResponse::success($taskWf);
    }

    public function taskActionSummary()
    {
        $orgId = jwt_claim('org_id');
        $taskActionSummary = $this->taskService->taskActionSummary($orgId);
        return APIResponse::success(TaskActionSummaryResource::collection($taskActionSummary));
    }

    public function approveTask(TaskUpdateRequest $taskUpdateRequest, string $id)
    {
        $data = $taskUpdateRequest->validated();
        $data["user_id"] = jwt_claim('sub');
        $taskUpdate = $this->taskService->approveTask($data, $id);
        return APIResponse::success($taskUpdate);
    }

    public function rejectTask(TaskUpdateRequest $taskUpdateRequest, string $id)
    {
        $data = $taskUpdateRequest->validated();
        $data["user_id"] = jwt_claim('sub');
        $taskUpdate = $this->taskService->rejectTask($data, $id);
        return APIResponse::success($taskUpdate);
    }


}
