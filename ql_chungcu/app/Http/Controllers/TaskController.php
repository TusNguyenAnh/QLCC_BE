<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest\TaskFilterRequest;
use App\Http\Requests\TaskRequest\TaskRequest;
use App\Http\Requests\TaskRequest\TaskUpdateRequest;
use App\Http\Resources\TaskActionSummaryResource;
use App\Http\Resources\TaskResource;
use App\Responses\APIResponse;
use App\Services\MediaFileService\IMediaFileService;
use App\Services\TaskService\ITaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected ITaskService $taskService;
    protected IMediaFileService $mediaFileService;

    public function __construct(ITaskService $taskService, IMediaFileService $mediaFileService)
    {
        $this->taskService = $taskService;
        $this->mediaFileService = $mediaFileService;
    }

    public function store(TaskRequest $taskRequest)
    {
        $data = $taskRequest->validated();
        $data["user_id"] = auth()->user()->id;
        $data["res_id"] = auth()->user()->res_id;
        $data["owner_type"] = "task";
        $data["files"] = $taskRequest->file('files');

        $task = $this->taskService->add($data);
        $mediaFile = $this->mediaFileService->add($data,$task["id"]);
        return APIResponse::success($task);
    }

    public function findByOrgId(TaskFilterRequest $taskFilterRequest, int $taskStatus, string $orgId)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        $filters = $taskFilterRequest->validated();
        $task = $this->taskService->findByOrgId($filters, $orgId, $taskStatus, $perPage);
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

    public function filterTaskApproved(TaskFilterRequest $taskFilterRequest, string $orgId)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        $filters = $taskFilterRequest->validated();
        $taskApproved = $this->taskService->filterTaskApproved($orgId, "APPROVED", $filters, $perPage);
        return APIResponse::paginated($taskApproved);
    }
}
