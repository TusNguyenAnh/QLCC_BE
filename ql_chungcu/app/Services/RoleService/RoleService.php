<?php

namespace App\Services\RoleService;

use App\Repositories\AuthorizationRepository\IRoleRepository;
use App\Repositories\AuthorizationRepository\IUserRoleRepository;

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
        $userRole = $this->userRoleRepository->store($data);
        return $userRole;
    }

    public function findByComplexId(string $complexId, string $perPage)
    {
        return $this->roleRepository->findByComplexId($complexId, $perPage);
    }
}
