<?php

namespace App\Services\UserService;

use App\Repositories\UserRepository\IUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    private IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(Request $request)
    {
        $email = $request->email;
        if ($this->userRepository->findByEmail($email)) {
            return false;
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false
        ];

        $user = $this->userRepository->store($data);

        return $user;
    }
}
