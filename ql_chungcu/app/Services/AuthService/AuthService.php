<?php

namespace App\Services\AuthService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Repositories\UserRepository\IUserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements IAuthService
{
    protected IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login($credentials) //on
    {
        if (!$token = JWTAuth::attempt($credentials)) { // neu dung $token se chua gia tri token
            throw new AppException(ErrorCode::INCORRECT_LOGIN_INFO);
        }

        $refreshToken = $this->createRefreshToken(); // tao rf token
        return $this->respondWithToken($token, $refreshToken); // tra ve token va rf token
    }

    public function logout($token, $refreshToken)
    {
        $userId = auth()->user()->id;
        auth()->logout();//dua token vao blacklist va token k sd dc nx
        //thu hoi refresh_token
        if ($refreshToken) {
            $this->userRepository->update($userId, ['refresh_token' => '']);
        }
        return response()->json(['message' => 'Đăng xuất thành công!'])
            ->withoutCookie('token', '/', 'localhost', false, true)
            ->withoutCookie('refresh_token', '/', 'localhost', false, true);
    }

    // lay thong tin chi tiet user thong qua token
    public function profile()
    {
        try {
            $user = auth()->user()->load(['resident']);

            $permissions = $user->roles
                ->flatMap(fn($role) => $role->permissions->pluck('name'))
                ->unique()
                ->values();

            unset($user->roles);

            return response()->json([
                'user' => $user,
                'permissions' => $permissions,
            ]);
        } catch (JWTException $ex) {
            throw new AppException(ErrorCode::UNAUTHORIZED);
        }
    }

    public function refresh($accessToken, $refreshToken)
    {
        try {
            //giai ma 2 token
            $decodeToken = JWTAuth::getJWTProvider()->decode($accessToken);
            $decodeRfToken = JWTAuth::getJWTProvider()->decode($refreshToken);

            // lay thong tin user
            $user = $this->userRepository->findById($decodeRfToken['user_id']);
            if (!$user) {
                throw new AppException(ErrorCode::USER_NON_EXISTED);
            }

            $refresh_token = $user->refresh_token;
            //lay time hien tai neu de so sanh vs exp cua token
            $now = Carbon::now()->timestamp;

            if ($now < $decodeToken['exp']) {
                JWTAuth::setToken($accessToken)->invalidate(); //vo hieu hoa access token hien tai trong truong hop no van con han
            }

            // kiem tra rf token gui len va trong db co trung nhau k
            if (($refresh_token != $refreshToken) || $now > $decodeRfToken['expires_rftoken']) {
                throw new AppException(ErrorCode::INCORRECT_RF_TOKEN);
            }

            // xu li cap lai token moi
            $token = auth()->login($user); // tao token moi
            $refreshToken = $this->createRefreshToken();

            return $this->respondWithToken($token, $refreshToken);
        } catch (TokenInvalidException $e) {
            throw new AppException(ErrorCode::TOKEN_INVALID);
        } catch (JWTException $ex) {
            throw new AppException(ErrorCode::INCORRECT_RF_TOKEN);
        }
    }

    protected function createRefreshToken()
    {
        $data = [
            'user_id' => auth()->user()->id,
            'random' => rand() . time(),
            'expires_rftoken' => time() + config('jwt.refresh_ttl')
        ];

        $refreshToken = JWTAuth::getJWTProvider()->encode($data);

        $this->userRepository->update($data['user_id'], ['refresh_token' => $refreshToken]);
        return $refreshToken;
    }

    protected function respondWithToken($token, $refreshToken)
    {
//        return response()->json([
//            'access_token' => $token,
//            'refresh_token' => $refreshToken,
//            //thoi gian song tinh theo giay; thay doi: config->jwt->ttl
//            // 'expires_token' => config('jwt.ttl') * 60
//        ]);

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            //thoi gian song tinh theo giay; thay doi: config->jwt->ttl
            // 'expires_token' => config('jwt.ttl') * 60
        ])
            ->cookie('token', $token, config('jwt.ttl'), '/', "localhost", false, true, false, "Lax") //name,value,thoi gian song,path,domain,secure,httponly,Giữ nguyên giá trị cookie không encode,samesite
            ->cookie('refresh_token', $refreshToken, config('jwt.refresh_ttl'), '/', "localhost", false, true, false, "Lax");
    }
}
