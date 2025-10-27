<?php

namespace App\Repositories\AuthorizationRepository;

use App\Models\UserRole;

class UserRoleRepository implements IUserRoleRepository
{
    public function store(array $data)
    {
        $userRole = UserRole::create($data)->fresh();
        return $userRole;
    }
}
