<?php

namespace App\Repositories\AuthorizationRepository;

interface IUserRoleRepository
{
    public function store(array $data);
    public function getRoleByUserId($userId);
    public function delete(string $userId);
}
