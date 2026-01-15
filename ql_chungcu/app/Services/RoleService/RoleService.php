<?php

namespace App\Services\RoleService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Repositories\AuthorizationRepository\IRoleRepository;
use App\Repositories\AuthorizationRepository\IUserRoleRepository;
use App\Repositories\OrgUserRepository\IOrgUserRepository;
use Illuminate\Support\Str;

class RoleService implements IRoleService
{

    private IRoleRepository $roleRepository;
    private IOrgUserRepository $orgUserRepository;

    public function __construct(IRoleRepository $roleRepository, IOrgUserRepository $orgUserRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->orgUserRepository = $orgUserRepository;
    }

    public function add(array $data)
    {
        $role = $this->roleRepository->store($data);
        return $role;
    }

    public function assignRole(array $data)
    {
        $orgUser = $this->orgUserRepository->findUserByOrgId($data['user_id'], $data['org_id']);

        if (!$orgUser) {
            throw new AppException(ErrorCode::NOT_FOUND);
        }
        return $this->orgUserRepository->update($data["user_id"], $data["org_id"], $data["role_id"]);
    }

    public function findByComplexId(string $complexId, string $perPage)
    {
        return $this->roleRepository->findByComplexId($complexId, $perPage);
    }

    public function getRoleByUserId($userId, $orgId)
    {
        return $this->orgUserRepository->getRoleByUserId($userId, $orgId);
    }
}
