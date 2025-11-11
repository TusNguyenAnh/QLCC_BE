<?php

namespace App\Repositories\AuthorizationRepository;

use App\Models\UserRole;

class UserRoleRepository implements IUserRoleRepository
{
    public function store(array $data)
    {
        $userRole = UserRole::insert($data);
        return $userRole;
    }

    public function getRoleByUserId($userId)
    {
        return UserRole::join('roles', 'user_role.role_id', '=', 'roles.id')
            ->where('user_role.user_id', $userId)
            ->pluck('user_role.role_id')
            ->toArray();

    }

    public function delete(string $userId)
    {
        UserRole::where('user_id', $userId)->delete();
    }
}
