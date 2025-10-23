<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskTypeRequest\TaskTypeRequest;
use App\Http\Resources\TaskTypeResource;
use App\Responses\APIResponse;
use App\Services\TaskService\ITaskTypeService;
use Illuminate\Http\Request;

class TaskTypeController extends Controller
{
    protected ITaskTypeService $taskTypeService;

    public function __construct(ITaskTypeService $taskTypeService)
    {
        $this->taskTypeService = $taskTypeService;
    }

    public function index(Request $request,string $complexId)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return APIResponse::paginated(TaskTypeResource::collection($this->taskTypeService->show($perPage, $complexId)));
    }

    public function store(TaskTypeRequest $taskTypeRequest)
    {
        $data = $taskTypeRequest->validated();
//        $data["user_id"] = auth()->user()->id;
        $tt = $this->taskTypeService->add($data);
        return APIResponse::success(new TaskTypeResource($tt));
    }

}
