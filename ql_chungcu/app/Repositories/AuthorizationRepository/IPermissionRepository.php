<?php

namespace App\Repositories\AuthorizationRepository;

interface IPermissionRepository
{
    public function store(array $data);
    public function getAllRole();

}
