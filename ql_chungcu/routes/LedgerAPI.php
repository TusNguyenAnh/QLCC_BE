<?php

use App\Http\Controllers\LedgerController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('ledgers')->group(function () {
    // Lấy danh sách sổ cái
    Route::post('/getLedger', [LedgerController::class, 'index']);
    Route::post('/storeRevenue/{revenueId}', [LedgerController::class, 'storeRevenue']);
    Route::post('/storeExpense/{expenseId}', [LedgerController::class, 'storeExpense']);
    Route::post('/createLedgerSummary', [LedgerController::class, 'createLedgerSummary']);
    Route::post('/updateLedgerSummary', [LedgerController::class, 'updateLedgerSummary']);
});
