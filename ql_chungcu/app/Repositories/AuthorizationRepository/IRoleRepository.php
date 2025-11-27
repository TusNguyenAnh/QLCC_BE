<?php

namespace App\Repositories\AuthorizationRepository;

interface IRoleRepository
{
    public function findByComplexId(string $complexId, string $perPage);
    public function store(array $data);
    public function findByRoleName(string $roleName);

}
