<?php

namespace App\Services\UserService;

use App\Models\User;
use Illuminate\Http\Request;

interface IUserService
{
    public function show($perPage);
    public function add(array $data) : ?User;
}
