<?php

namespace App\Repositories\UserRepository;

interface IUserRepository
{
    public function findByEmail($email);
    public function store(array $data);
}
