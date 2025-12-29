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

Route::group([
    'middleware' => 'auth:api',
    'prefix' => '/complex',
],
    function () {
        Route::get('/findById/{id}', [ComplexController::class, 'findById']);
        Route::post('/filterComplex/{status}', [ComplexController::class, 'filterComplex']);
        Route::post('/create', [ComplexController::class, 'store']);
        Route::post('/approveComplex', [ComplexController::class, 'approveComplex']);
        Route::post('/rejectComplex', [ComplexController::class, 'rejectComplex']);
    });

