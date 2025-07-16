<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create(Request $request)
    {
        //
        return json_encode($this->userService->createUser($request));
    }
}
