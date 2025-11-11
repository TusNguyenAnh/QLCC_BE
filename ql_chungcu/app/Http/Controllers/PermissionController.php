<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest\PermissionAssignRequest;
use App\Http\Requests\PermissionRequest\PermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Responses\APIResponse;
use App\Services\PermissionService\IPermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected IPermissionService $permissionService;

    public function __construct(IPermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function store(PermissionRequest $permissionRequest)
    {
        $data = $permissionRequest->validated();

        $permission = $this->permissionService->add($data);
        return APIResponse::success($permission);
    }
    public function assignPermission(PermissionAssignRequest $permissionAssignRequest)
    {
        $data = $permissionAssignRequest->validated();
        $permissionAssign = $this->permissionService->assignPermission($data);
        return APIResponse::success($permissionAssign);
    }

    public function getAllRole(){
        $allRole = $this->permissionService->getAllRole();
        return APIResponse::success($allRole);
    }

}
