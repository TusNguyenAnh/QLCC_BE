<?php

namespace App\Services\UserService;

use Illuminate\Http\Request;

interface IUserService
{
    public function createUser(Request $request);
}
