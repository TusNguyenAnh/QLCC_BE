<?php

namespace App\Repositories\UserRepository;

interface IUserRepository
{
    public function show($perPage);

    public function findById($id);

    public function findByUsername($username);

    public function store(array $data);

    public function update($id, array $data);

    public function getBuildingIdsManage($userId);
}
