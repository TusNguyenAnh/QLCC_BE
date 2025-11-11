<?php

namespace App\Services\UserService;

use App\Models\User;
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
    public function show($perPage)
    {
        return $this->userRepository->show($perPage);
    }

    public function add(array $data) : ?User
    {
        $username = $data["username"];
        if ($this->userRepository->findByUsername($username)) {
            return null;
        }

//        $data = [
//            'username' => $request->username,
//            'fullname' => $request->fullname,
//            'phone_number' => $request->phone_number,
//            'address' => $request->address,
//            'email' => $request->email,
//            'password' => Hash::make($request->password),
//            'is_admin' => false
//        ];

        $user = $this->userRepository->store($data);

        return $user;
    }

    public function findByOrgId($orgId, $perPage = 10)
    {
        $listUser = $this->userRepository->findByOrgId($orgId, $perPage);
        return $listUser;
    }
}
