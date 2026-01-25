<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth:api',
    'prefix' => '/expenses'],
    function () {
        // Lấy danh sách khoản chi
        Route::post('/getExpense', [ExpenseController::class, 'index']);

        // Tạo khoản chi mới
        Route::post('/', [ExpenseController::class, 'store']);

        // Cập nhật khoản chi
        Route::put('/{id}', [ExpenseController::class, 'update']);

        // Xóa khoản chi
        Route::delete('/{id}', [ExpenseController::class, 'delete']);

        // Duyệt khoản chi
        Route::post('/approve', [ExpenseController::class, 'approveExpense']);
    });

