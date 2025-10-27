<?php

namespace App\Repositories\AuthorizationRepository;

use App\Models\Role;

class RoleRepository implements IRoleRepository
{
    public function store(array $data)
    {
        $role = Role::create($data)->fresh();
        return $role;
    }

    public function findByComplexId(string $complexId, string $perPage)
    {
        return Role::where('complex_id', $complexId)
            ->paginate($perPage);
    }
}
