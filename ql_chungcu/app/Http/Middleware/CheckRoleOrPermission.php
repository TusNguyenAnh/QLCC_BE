<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleOrPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle($request, Closure $next, $permission)
    {
        $user = auth()->user();
        if (!in_array($permission, $user->getJWTCustomClaims()['permissions'] ?? [])) {
            return response()->json([
                'message' => 'Bạn không có quyền truy cập vào tài nguyên',
                'code' => '0000'
            ], 403);
        }
        return $next($request);
    }
}
