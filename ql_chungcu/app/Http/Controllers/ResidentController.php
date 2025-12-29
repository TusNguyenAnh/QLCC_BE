<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest\ExcelFileRequest;
use App\Http\Requests\ResidentRequest\ResidentFilterRequest;
use App\Http\Requests\ResidentRequest\ResidentRequest;
use App\Http\Requests\ResidentRequest\ResidentImportRequest;
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

    public function index(ResidentFilterRequest $residentFilterRequest)
    {
        $filters = $residentFilterRequest->validated();
        $residents = $this->residentService->show($filters);
        return APIResponse::success(ResidentResource::collection($residents));
    }

    public function store(ResidentRequest $residentRequest)
    {
        $data = $residentRequest->validated();
        $data["complex_id"] = jwt_claim('complex_id');
        $resident = $this->residentService->add($data);
        return APIResponse::success(new ResidentResource($resident));
    }

    public function findByOrgId($orgId)
    {
        $res = $this->residentService->findByOrgId($orgId);
        return APIResponse::success(ResidentResource::collection($res));
    }

    public function findResidentByBuildingId(Request $request)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return $this->residentService->findResidentByBuildingId($request["building_id"], $perPage);
    }

    public function updateResInOrg(Request $request, string $org_id)
    {
        return $this->residentService->updateResInOrg($request["res_id"], $org_id);
    }

    /**
     * Import residents from Excel file
     * Validates all rows before saving to database
     * Returns specific errors with row numbers if validation fails
     */
    public function importResExcel(ExcelFileRequest $request)
    {
        $file = $request->file('files');

        $result = $this->residentService->importResFromExcel($file);

        if ($result['success']) {
            return APIResponse::success($result);
        } else {
            return APIResponse::error($result['message'], 400);
        }
    }

    public function importResAptExcel(ExcelFileRequest $request)
    {
        $file = $request->file('files');

        $result = $this->residentService->importResAptFromExcel($file);

        if ($result['success']) {
            return APIResponse::success($result);
        } else {
            return APIResponse::error($result['message'], 400);
        }
    }
}
