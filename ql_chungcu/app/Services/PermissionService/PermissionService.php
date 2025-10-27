<?php

namespace App\Services\PermissionService;

use App\Repositories\AuthorizationRepository\IPermissionRepository;
use App\Repositories\AuthorizationRepository\IRolePermissionRepository;

class PermissionService implements IPermissionService
{

    private IPermissionRepository $permissionRepository;
    private IRolePermissionRepository $rolePermissionRepository;

    public function __construct(IPermissionRepository $permissionRepository, IRolePermissionRepository $rolePermissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->rolePermissionRepository = $rolePermissionRepository;
    }

    public function add(array $data)
    {
        $permission = $this->permissionRepository->store($data);
        return $permission;
    }

    public function assignPermission(array $data)
    {
        $rolePermission = $this->rolePermissionRepository->store($data);
        return $rolePermission;
    }

    public function getAllRole()
    {
        return $this->permissionRepository->getAllRole();
    }
}
