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

    public function index(Request $request)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return APIResponse::paginated(WorkflowResource::collection($this->workflowService->show($perPage)));
    }

    public function store(WorkflowRequest $workflowRequest)
    {
        $data = $workflowRequest->validated();
//        $data["user_id"] = auth()->user()->id;
        $wf = $this->workflowService->add($data);
        return APIResponse::success(new WorkflowResource($wf));
    }

    public function update(BuildingRequest $buildingRequest, string $id)
    {
//        $data = $buildingRequest->validated();
////        $data["user_id"] = auth()->user()->id;
//        $bdUpdate = $this->buildingService->update($id, $data);
//        return APIResponse::success(new BuildingResource($bdUpdate));
    }

    public function destroy(Request $request)
    {
//        $listBd = $request->input('listBd');
////        return $listOrg;
//        $this->buildingService->delete($listBd);
    }
}
