<?php

namespace App\Services\PermissionService;

use App\Repositories\AuthorizationRepository\IPermissionRepository;
use App\Repositories\AuthorizationRepository\IRolePermissionRepository;
use Illuminate\Support\Str;

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
        $this->rolePermissionRepository->delete($data["role_id"]);

        $dataRolePerm = [];

        foreach ($data["permission"] as $perm) {
            $dataRolePerm[] = [
                'id' => (string)Str::uuid(),
                'role_id' => $data["role_id"],
                'permission_id' => $perm
            ];
        }

        return $this->rolePermissionRepository->store($dataRolePerm);
    }

    public function getAllRole()
    {
        return $this->permissionRepository->getAllRole();
    }
}
