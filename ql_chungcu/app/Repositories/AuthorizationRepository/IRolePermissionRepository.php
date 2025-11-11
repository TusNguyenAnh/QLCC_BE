<?php

namespace App\Repositories\AuthorizationRepository;

interface IRolePermissionRepository
{
    public function store(array $data);
    public function delete(string $roleId);
}
