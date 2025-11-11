<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest\RoleAssignRequest;
use App\Http\Requests\RoleRequest\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Responses\APIResponse;
use App\Services\RoleService\IRoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected IRoleService $roleService;

    public function __construct(IRoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function store(RoleRequest $roleRequest)
    {
        $data = $roleRequest->validated();

        $role = $this->roleService->add($data);
        return APIResponse::success($role);
    }

    public function assignRole(RoleAssignRequest $roleAssignRequest)
    {
        $data = $roleAssignRequest->validated();

        $roleAssign = $this->roleService->assignRole($data);
        return APIResponse::success($roleAssign);
    }

    public function findByComplexId(string $complexId)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return APIResponse::paginated(RoleResource::collection($this->roleService->findByComplexId($complexId, $perPage)));
    }

    public function getRoleByUserId($userId)
    {
        return APIResponse::success($this->roleService->getRoleByUserId($userId));
    }


}
