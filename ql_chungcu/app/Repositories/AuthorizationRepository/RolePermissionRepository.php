<?php

namespace App\Repositories\AuthorizationRepository;

use App\Models\RolePermission;

class RolePermissionRepository implements IRolePermissionRepository
{
    public function store(array $data)
    {
        $rolePermission = RolePermission::insert($data);
        return $rolePermission;
    }

    public function delete(string $roleId)
    {
        RolePermission::where('role_id', $roleId)->forceDelete();
    }
}
