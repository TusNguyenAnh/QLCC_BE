<?php

use Tymon\JWTAuth\Facades\JWTAuth;

if (!function_exists('jwt_claim')) {
    /**
     * Lấy ra giá trị của 1 field trong JWT token
     *
     * @param string $key     Tên field cần lấy (vd: 'org_id', 'role', ...)
     * @param mixed  $default Giá trị mặc định nếu token không hợp lệ hoặc không có field
     * @return mixed
     */
    function jwt_claim(string $key, $default = null)
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            return $payload->get($key, $default);
        } catch (Exception $e) {
            return $default;
        }
    }
}
