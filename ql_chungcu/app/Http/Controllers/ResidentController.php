<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResidentRequest\ResidentRequest;
use App\Http\Resources\ResidentResource;
use App\Models\Resident;
use App\Responses\APIResponse;
use App\Services\ResidentService\IResidentService;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    protected IResidentService $residentService;

    public function __construct(IResidentService $residentService)
    {
        $this->residentService = $residentService;
    }

    public function index()
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return APIResponse::paginated(ResidentResource::collection($this->residentService->show($perPage)));
    }

    public function store(ResidentRequest $residentRequest)
    {
        $data = $residentRequest->validated();
        $data["res_id"] = "1";
        $resident = $this->residentService->add($data);
        return APIResponse::success(new ResidentResource($resident));
    }

    public function findByOrgId($orgId){
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return $this->residentService->findByOrgId($orgId, $perPage);
    }

    public function findResidentByBuildingId(Request $request){
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return $this->residentService->findResidentByBuildingId($request["building_id"], $perPage);
    }

    public function addResInOrg(Request $request,string $org_id){
        return $this->residentService->addResInOrg($request["res_id"], $org_id);
    }
}
