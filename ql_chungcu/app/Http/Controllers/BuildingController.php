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

    public function index(Request $request)
    {
        $complexId = jwt_claim('complex_id');
        return APIResponse::success(BuildingResource::collection($this->buildingService->show($complexId)));
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

    public function updateRatio(Request $request)
    {
        $data = $request->all();
        $data['complex_id'] = jwt_claim('complex_id');
        $this->buildingService->updateRatio($data);
        return APIResponse::success('Cập nhật tỉ lệ thành công!');
    }
}
