<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApartmentRequest\ApartmentRequest;
use App\Http\Requests\FileRequest\ExcelFileRequest;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use App\Responses\APIResponse;
use App\Services\ApartmentService\IApartmentService;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    protected IApartmentService $apartmentService;

    public function __construct(IApartmentService $apartmentService)
    {
        $this->apartmentService = $apartmentService;
    }

    public function findByBuildingId(string $bdId)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return APIResponse::paginated(ApartmentResource::collection($this->apartmentService->findByBuildingId($bdId, $perPage)));
    }

    public function store(ApartmentRequest $apartmentRequest)
    {
        $data = $apartmentRequest->validated();
        $data["complex_id"] = jwt_claim('complex_id');
        $apt = $this->apartmentService->add($data);
        return APIResponse::success(new ApartmentResource($apt));
    }

    public function update(ApartmentRequest $apartmentRequest, string $id)
    {
        $data = $apartmentRequest->validated();
//        $data["user_id"] = auth()->user()->id;
        $aptUpdate = $this->apartmentService->update($id, $data);
        return APIResponse::success(new ApartmentResource($aptUpdate));
    }

    public function importAptExcel(ExcelFileRequest $request)
    {
        $file = $request->file('files');

        $result = $this->apartmentService->importAptFromExcel($file);

        if ($result['success']) {
            return APIResponse::success($result);
        } else {
            return APIResponse::error($result['message'], 400);
        }
    }
}
