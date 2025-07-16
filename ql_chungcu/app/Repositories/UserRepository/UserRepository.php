<?php

namespace App\Repositories\UserRepository;

use App\Models\User;

class UserRepository implements IUserRepository
{
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function store(array $data)
    {
        return User::create($data);
    }
}
