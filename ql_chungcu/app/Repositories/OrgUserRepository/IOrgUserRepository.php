<?php

namespace App\Repositories\OrgUserRepository;

interface IOrgUserRepository
{
    public function findByOrgId(string $orgId, array $role_id);
    public function store(array $data);
    public function update(string $userId, string $orgId, string $role_id);
    public function delete(array $userIds, string $org_id);
    public function getRoleByUserId(string $userId,string $org_id);
    public function findUserByOrgId(string $userId, string $org_id);
}
