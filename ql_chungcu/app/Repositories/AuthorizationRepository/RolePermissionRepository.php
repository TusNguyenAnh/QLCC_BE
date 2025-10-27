<?php

namespace App\Repositories\AuthorizationRepository;

use App\Models\RolePermission;

class RolePermissionRepository implements IRolePermissionRepository
{
    public function store(array $data)
    {
        $rolePermission = RolePermission::create($data)->fresh();
        return $rolePermission;
    }
}
