<?php

namespace App\Services\PermissionService;

interface IPermissionService
{
    public function add(array $data);
    public function assignPermission(array $data);
    public function getAllRole();

}
