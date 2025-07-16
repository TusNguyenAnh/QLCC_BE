<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    public function login() //on
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) { // neu dung $token se chua gia tri token
            return response()->json([
                'error' => 'Sai thông tin đăng nhập!',
                'code' => 1003
            ], 401);
        }

        $refreshToken = $this->createRefreshToken();
        return $this->respondWithToken($token, $refreshToken);
    }

    public function logout() //on
    {
        auth()->logout(); //dua token vao blacklist va token k sd dc nx

        //thu hoi refresh_token
        if ($refreshToken = request()->refresh_token) {
            User::where('refresh_token', $refreshToken)->update(['refresh_token' => '']);
        }
        return response()->json(['message' => 'Đăng xuất thành công!']);
    }

    // sd trong viec tao token moi va vo hieu hoa token cu
    public function refresh() //on
    {
        $accessToken = request()->access_token;
        $refreshToken = request()->refresh_token;
        try {
            $decodeToken = JWTAuth::getJWTProvider()->decode($accessToken);
            $decodeRfToken = JWTAuth::getJWTProvider()->decode($refreshToken);

            // lay thong tin user
            $user = User::find($decodeRfToken['user_id']);
            $refresh_token = User::find($decodeRfToken['user_id'])->refresh_token;
            if (!$user) {
                return response()->json([
                    'message' => 'User không tồn tại!',
                    'code' => 3000
                ], 404);
            }
            //lay time hien tai neu de so sanh vs exp cua token
            $now = Carbon::now()->timestamp;

            if ($now < $decodeToken['exp']) {
                JWTAuth::setToken($accessToken)->invalidate(); //vo hieu hoa access token hien tai trong truong hop no van con han
            }

            if (($refresh_token != $refreshToken) || $now > $decodeRfToken['expires_rfftoken']) {
                return response()->json([
                    'message' => 'Refresh token không hợp lệ hoặc hết hạn',
                    'code' => 1004
                ], 401);
            }


            // xu li cap lai token moi
            $token = auth()->login($user); // tao token moi
            $refreshToken = $this->createRefreshToken();

            return $this->respondWithToken($token, $refreshToken);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'message' => 'Token không hợp lệ',
                'code' => '1001'
            ], 401);
        } catch (JWTException $ex) {
            return response()->json([
                'message' => 'Refresh token không hợp lệ',
                'code' => 1004
            ], 401);
        }
    }

    public function profile()
    {
        try {
            return response()->json(auth()->user());
        } catch (JWTException $ex) {
            return response()->json(['message' => 'Bạn không có quyền!'], 401);
        }
    }

    protected function respondWithToken($token, $refreshToken)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            //thoi gian song tinh theo giay; thay doi: config->jwt->ttl
            // 'expires_token' => config('jwt.ttl') * 60
        ]);
    }

    protected function createRefreshToken()
    {
        $data = [
            'user_id' => auth()->user()->id,
            'random' => rand() . time(),
            'expires_rfftoken' => time() + config('jwt.refresh_ttl')
        ];

        $refreshToken = JWTAuth::getJWTProvider()->encode($data);
        User::find($data['user_id'])->update(['refresh_token' => $refreshToken]);
        return $refreshToken;
    }
}
