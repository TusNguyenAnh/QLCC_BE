<?php

namespace App\Services\AuthService;

interface IAuthService
{
    public function login($credentials);

    public function logout($token,$refreshToken);

    // lay thong tin chi tiet user thong qua token
    public function profile();

    public function refresh($accessToken, $refreshToken);

}
