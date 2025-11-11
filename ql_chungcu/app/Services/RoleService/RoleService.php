<?php

namespace App\Services\RoleService;

use App\Repositories\AuthorizationRepository\IRoleRepository;
use App\Repositories\AuthorizationRepository\IUserRoleRepository;
use Illuminate\Support\Str;

class RoleService implements IRoleService
{

    private IRoleRepository $roleRepository;
    private IUserRoleRepository $userRoleRepository;

    public function __construct(IRoleRepository $roleRepository, IUserRoleRepository $userRoleRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->userRoleRepository = $userRoleRepository;
    }

    public function add(array $data)
    {
        $role = $this->roleRepository->store($data);
        return $role;
    }

    public function assignRole(array $data)
    {
        $this->userRoleRepository->delete($data["user_id"]);

        $dataUserRole = [];
        foreach ($data["role"] as $role) {
            $dataUserRole[] = [
                'id' => (string)Str::uuid(),
                'user_id' => $data["user_id"],
                'role_id' => $role
            ];
        }

        return $this->userRoleRepository->store($dataUserRole);
    }

    public function findByComplexId(string $complexId, string $perPage)
    {
        return $this->roleRepository->findByComplexId($complexId, $perPage);
    }

    public function getRoleByUserId($userId)
    {
        return $this->userRoleRepository->getRoleByUserId($userId);
    }
}
