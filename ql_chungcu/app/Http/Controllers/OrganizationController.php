<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationRequest\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Responses\APIResponse;
use App\Services\OrganizationService\IOrgService;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    protected IOrgService $orgService;

    public function __construct(IOrgService $orgService)
    {
        $this->orgService = $orgService;
    }

    public function index()
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return APIResponse::paginated(OrganizationResource::collection($this->orgService->show($perPage)));
    }

    public function getAllWithoutChild(string $parentOrgId)
    {
        return APIResponse::success($this->orgService->getAllWithoutChild($parentOrgId));
    }

    public function store(OrganizationRequest $organizationRequest)
    {
        $data = $organizationRequest->validated();
//        $data["user_id"] = auth()->user()->id;
        $org = $this->orgService->add($data);
        return APIResponse::success(new OrganizationResource($org));
    }

    public function update(OrganizationRequest $organizationRequest, string $id)
    {
        $data = $organizationRequest->validated();
//        $data["user_id"] = auth()->user()->id;
        $orgUpdate = $this->orgService->update($id, $data);
        return APIResponse::success(new OrganizationResource($orgUpdate));
    }

    public function destroy(Request $request)
    {
        $listOrg = $request->input('listOrg');
//        return $listOrg;
        $this->orgService->delete($listOrg);
    }
}
