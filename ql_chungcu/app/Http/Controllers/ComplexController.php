<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplexRequest\ComplexFilterRequest;
use App\Http\Requests\ComplexRequest\ComplexRequest;
use App\Http\Resources\ComplexResource;
use App\Models\Complex;
use App\Responses\APIResponse;
use App\Services\ComplexService\IComplexService;
use App\Services\MediaFileService\IMediaFileService;
use Illuminate\Http\Request;

class ComplexController extends Controller
{
    protected IComplexService $complexService;
    protected IMediaFileService $mediaFileService;

    public function __construct(IComplexService $complexService, IMediaFileService $mediaFileService)
    {
        $this->complexService = $complexService;
        $this->mediaFileService = $mediaFileService;
    }

    public function findById(string $id)
    {
        $complex = $this->complexService->findById($id);
        return APIResponse::success(new ComplexResource($complex));
    }


    public function store(ComplexRequest $complexRequest)
    {
        $data = $complexRequest->validated();
        $data["owner_type"] = "complex";
        $data["files"] = $complexRequest->file('files');

        $complex = $this->complexService->add($data);
        $mediaFile = $this->mediaFileService->add($data, $complex["id"]);
        return APIResponse::success($complex);
    }

    public function filterComplex(ComplexFilterRequest $complexFilterRequest, int $status)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        $filters = $complexFilterRequest->validated();
        return APIResponse::paginated(ComplexResource::collection($this->complexService->show($filters, $status, $perPage)));
    }

    public function approveComplex(Request $request)
    {
        $cpls = $request->get("ids");
        $cplApprove = $this->complexService->approveComplex($cpls);
        return APIResponse::success($cplApprove);
    }

    public function rejectComplex(Request $request)
    {
        $cpls = $request->get("ids");
        return APIResponse::success($this->complexService->rejectComplex($cpls));
    }
}
