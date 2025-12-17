<?php

use App\Http\Controllers\AdjustmentTransactionController;
use Illuminate\Support\Facades\Route;

Route::group([
//    'middleware' => 'auth:api',
    'prefix' => '/adjustments'],
    function () {
        // Lấy bút toán điều chỉnh theo revenue/expense ID
        Route::get('/reference/{referenceId}', [AdjustmentTransactionController::class, 'getByReference']);

        // Tao bút toán điều chỉnh
        Route::post('/create/{ledgerId}', [AdjustmentTransactionController::class, 'store']);
    });

