<?php

namespace App\Http\Controllers;

use App\Http\Resources\PriorityResource;
use App\Responses\APIResponse;
use App\Services\PriorityService\IPriorityService;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    protected IPriorityService $priorityService;

    public function __construct(IPriorityService $priorityService)
    {
        $this->priorityService = $priorityService;
    }

    public function index()
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return APIResponse::paginated(PriorityResource::collection($this->priorityService->show($perPage)));
    }
}
