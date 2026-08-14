<?php

use App\Http\Controllers\MoneyAccountController;
use Illuminate\Support\Facades\Route;

Route::prefix('money-accounts')->group(function () {
    // GET: Lấy danh sách theo tòa nhà
    Route::get('{buildingId}', [MoneyAccountController::class, 'findByBuildingId']);

    // POST: Tạo mới
    Route::post('/', [MoneyAccountController::class, 'store']);

    // POST: Import Excel
    Route::post('/import', [MoneyAccountController::class, 'importMoneyAccExcel']);
});





