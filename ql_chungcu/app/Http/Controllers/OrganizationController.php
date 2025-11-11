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

    public function findById(string $id)
    {
        return APIResponse::success($this->orgService->findById($id));
    }

    public function getAllWithoutChild(string $parentOrgId, string $complexId)
    {
        return APIResponse::success($this->orgService->getAllWithoutChild($parentOrgId, $complexId));
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

    public function getTopLevel(string $complex_id)
    {
        return APIResponse::success($this->orgService->getTopLevel($complex_id));
    }

    public function getBdIdByParentOrgId(string $complexId, string $parentId = null)
    {
        return APIResponse::success($this->orgService->getBdIdByParentOrgId($complexId, $parentId));
    }


}
