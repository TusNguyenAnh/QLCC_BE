<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffRequest\StaffRequest;
use App\Http\Resources\StaffResource;
use App\Responses\APIResponse;
use App\Services\StaffService\IStaffService;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    protected IStaffService $staffService;

    public function __construct(IStaffService $staffService)
    {
        $this->staffService = $staffService;
    }

//    public function index(ResidentFilterRequest $residentFilterRequest)
//    {
//        $filters = $residentFilterRequest->validated();
//        $residents = $this->residentService->show($filters);
//        return APIResponse::success(ResidentResource::collection($residents));
//    }

    public function store(StaffRequest $request)
    {
        $data = $request->validated();
        $data["complex_id"] = jwt_claim('complex_id');
        $staff = $this->staffService->add($data);
        return APIResponse::success(new StaffResource($staff));
    }
}
