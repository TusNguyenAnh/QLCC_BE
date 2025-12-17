<?php

use App\Http\Controllers\ServiceUnitPriceController;
use Illuminate\Support\Facades\Route;

Route::group([
//    'middleware' => 'auth:api',
    'prefix' => '/service-unit-prices'],
    function () {
        // Lấy tất cả đơn giá dịch vụ
        Route::get('/', [ServiceUnitPriceController::class, 'index']);

        // Lấy đơn giá theo năm
        Route::get('/year/{year}', [ServiceUnitPriceController::class, 'getByYear']);

        // Tạo đơn giá dịch vụ mới
        Route::post('/', [ServiceUnitPriceController::class, 'store']);

        // Cập nhật đơn giá dịch vụ
        Route::put('/{id}', [ServiceUnitPriceController::class, 'update']);

        // Xóa đơn giá dịch vụ
        Route::delete('/{id}', [ServiceUnitPriceController::class, 'delete']);
    });

