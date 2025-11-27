<?php

use App\Http\Controllers\ComplexController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => '/complex'],
    function () {
        Route::post('/filterComplex/{status}', [ComplexController::class, 'filterComplex']);
        Route::post('/create', [ComplexController::class, 'store']);
        Route::post('/approveComplex', [ComplexController::class, 'approveComplex']);
        Route::post('/rejectComplex', [ComplexController::class, 'rejectComplex']);
    });

