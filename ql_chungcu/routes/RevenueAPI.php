<?php

use App\Http\Controllers\RevenueController;
use Illuminate\Support\Facades\Route;

Route::group([
//    'middleware' => 'auth:api',
    'prefix' => '/revenues'],
    function () {
        // Lấy danh sách khoản thu
        Route::post('/getRevenue', [RevenueController::class, 'index']);

        // Tạo khoản thu mới
        Route::post('/', [RevenueController::class, 'store']);

        // Tự động tạo khoản thu cho 12 tháng
        Route::post('/generate-monthly', [RevenueController::class, 'generateMonthlyRevenues']);

        // Cập nhật khoản thu
        Route::put('/{id}', [RevenueController::class, 'update']);

        // Xóa khoản thu
        Route::delete('/{id}', [RevenueController::class, 'delete']);
    });

