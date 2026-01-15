<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkflowRequest\WorkflowRequest;
use App\Http\Resources\WorkflowResource;
use App\Responses\APIResponse;
use App\Services\WorkflowService\IWorkflowService;
use Illuminate\Http\Request;

class WorkflowController
{
    protected IWorkflowService $workflowService;

    public function __construct(IWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function index(Request $request, string $complexId)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return APIResponse::paginated(WorkflowResource::collection($this->workflowService->show($perPage, $complexId)));
    }

    public function store(WorkflowRequest $workflowRequest)
    {
        $data = $workflowRequest->validated();
        $wf = $this->workflowService->add($data);
        return APIResponse::success(new WorkflowResource($wf));
    }
}
