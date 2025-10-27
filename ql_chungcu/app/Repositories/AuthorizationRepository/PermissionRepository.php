<?php

namespace App\Repositories\AuthorizationRepository;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionRepository implements IPermissionRepository
{

    public function store(array $data)
    {
        $permission = Permission::create($data)->fresh();
        return $permission;
    }

    public function getAllRole()
    {
//        return Permission::all()->groupBy('module')->toArray();
        return Permission::leftJoin('role_permiss', 'permissions.id', '=', 'role_permiss.permission_id')
            ->select(
                'permissions.id',
                'permissions.name',
                'permissions.module',
                'permissions.description',

                DB::raw('COUNT(role_permiss.role_id) as total_roles')
            )
            ->groupBy('permissions.id', 'permissions.name', 'permissions.module','permissions.description')
            ->get()
            ->groupBy('module');
    }
}
