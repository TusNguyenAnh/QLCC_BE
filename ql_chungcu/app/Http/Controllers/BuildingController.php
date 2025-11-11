<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuildingRequest\BuildingRequest;
use App\Http\Resources\BuildingResource;
use App\Responses\APIResponse;
use App\Services\BuildingService\IBuildingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BuildingController extends Controller
{
    protected IBuildingService $buildingService;

    public function __construct(IBuildingService $buildingService)
    {
        $this->buildingService = $buildingService;
    }

    public function index(Request $request,string $complexId)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return APIResponse::paginated(BuildingResource::collection($this->buildingService->show($complexId,$perPage)));
    }

    public function store(BuildingRequest $buildingRequest)
    {
        $data = $buildingRequest->validated();
//        $data["user_id"] = auth()->user()->id;
        $bd = $this->buildingService->add($data);
        return APIResponse::success(new BuildingResource($bd));
    }

    public function update(BuildingRequest $buildingRequest, string $id)
    {
        $data = $buildingRequest->validated();
//        $data["user_id"] = auth()->user()->id;
        $bdUpdate = $this->buildingService->update($id, $data);
        return APIResponse::success(new BuildingResource($bdUpdate));
    }

    public function destroy(Request $request)
    {
        $listBd = $request->input('listBd');
//        return $listOrg;
        $this->buildingService->delete($listBd);
    }
}
