<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService\IAuthService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login() //on
    {
        $credentials = request(['username', 'password']);
        return $this->authService->login($credentials);
    }

    public function logout(Request $request)
    {
        $token = $request->cookie('token');
        $refreshToken = $request->cookie('refresh_token');
        return $this->authService->logout($token,$refreshToken);
    }

    // sd trong viec tao token moi va vo hieu hoa token cu
    public function refresh() //on
    {
        $accessToken = request()->access_token;
        $refreshToken = request()->refresh_token;
        return $this->authService->refresh($accessToken, $refreshToken);
    }

    public function profile()
    {
        return $this->authService->profile();
    }
}
