<?php

namespace App\Services\RoleService;

interface IRoleService
{
    public function add(array $data);
    public function assignRole(array $data);
    public function findByComplexId(string $complexId, string $perPage);

    public function getRoleByUserId($userId);
}
