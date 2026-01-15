<?php

namespace App\Services\AuthService;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Repositories\AuthorizationRepository\IRoleRepository;
use App\Repositories\OrgUserRepository\IOrgUserRepository;
use App\Repositories\ResidentRepository\IResidentRepository;
use App\Repositories\UserRepository\IUserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements IAuthService
{
    protected IUserRepository $userRepository;
    protected IOrgUserRepository $orgUserRepository;
    protected IRoleRepository $roleRepository;


    public function __construct(IUserRepository $userRepository, IOrgUserRepository $orgUserRepository,
                                IRoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->orgUserRepository = $orgUserRepository;
        $this->roleRepository = $roleRepository;
    }

    public function login($credentials) //on
    {
        $username = $credentials['username'];
        $password = $credentials['password'];
        $complexId = $credentials['complex_id'] ?? "";
        $orgId = $credentials['org_id'] ?? "";

        $user = $this->userRepository->findByUsername($username, $complexId);

        if (!$user || !Hash::check($password, $user->password)) {
            throw new AppException(ErrorCode::INCORRECT_LOGIN_INFO);
        }

        $orgUser = $this->orgUserRepository->findUserByOrgId($user->id, $orgId);

        if (!$orgUser) {
            throw new AppException(ErrorCode::INCORRECT_LOGIN_INFO);
        }

        $role = $this->roleRepository->findByRoleId($orgUser->role_id);

        $customClaims = [
            'complex_id' => $complexId,
            'org_id' => $orgId,
            'role' => $role->role_name,
            'permissions' => $role->permissions->pluck('name'),
        ];

        // Tạo token với custom claims
        $token = JWTAuth::claims($customClaims)->fromUser($user);

//        if (!$token = JWTAuth::attempt($credentials)) { // neu dung $token se chua gia tri token
//            throw new AppException(ErrorCode::INCORRECT_LOGIN_INFO);
//        }

        $refreshToken = $this->createRefreshToken($user); // tao rf token
        return $this->respondWithToken($token, $refreshToken); // tra ve token va rf token
    }

    public function logout($token, $refreshToken)
    {
        $userId = auth()->user()->id;
        auth()->logout(); //dua token vao blacklist va token k sd dc nx
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

            //lay permission
            $orgId = jwt_claim('org_id');
            $orgUser = $this->orgUserRepository->findUserByOrgId($user->id, $orgId);
            $role = $this->roleRepository->findByRoleId($orgUser->role_id);

            return response()->json([
                'org_id' => $orgId,
                'user' => $user,
                'permissions' => $role->permissions->pluck('name'),
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
            $refreshToken = $this->createRefreshToken($user);

            return $this->respondWithToken($token, $refreshToken);
        } catch (TokenInvalidException $e) {
            throw new AppException(ErrorCode::TOKEN_INVALID);
        } catch (JWTException $ex) {
            throw new AppException(ErrorCode::INCORRECT_RF_TOKEN);
        }
    }

    protected function createRefreshToken($user)
    {
        $data = [
            'user_id' => $user->id,
            'random' => rand() . time(),
            'expires_rftoken' => time() + config('jwt.refresh_ttl')
        ];

        $refreshToken = JWTAuth::getJWTProvider()->encode($data);

        $this->userRepository->update($data['user_id'], ['refresh_token' => $refreshToken]);
        return $refreshToken;
    }

    protected function respondWithToken($token, $refreshToken)
    {
        return response()->json([
            'message' => 'Đăng nhập thành công',
            'access_token' => $token,
//            'refresh_token' => $refreshToken,
            //thoi gian song tinh theo giay; thay doi: config->jwt->ttl
            // 'expires_token' => config('jwt.ttl') * 60
        ])
            ->cookie('token', $token, config('jwt.ttl'), '/', config("JWT_COOKIE_DOMAIN"), false, true, false, "Lax") //name,value,thoi gian song,path,domain,secure,httponly,Giữ nguyên giá trị cookie không encode,samesite
            ->cookie('refresh_token', $refreshToken, config('jwt.refresh_ttl'), '/', config("JWT_COOKIE_DOMAIN"), false, true, false, "Lax");
    }
}
