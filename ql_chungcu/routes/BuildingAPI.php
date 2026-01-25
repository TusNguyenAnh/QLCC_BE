<?php

use App\Http\Controllers\BuildingController;
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
    'prefix' => '/bd'],
    function () {
        Route::get('', [BuildingController::class, 'index']);
        Route::post('/create', [BuildingController::class, 'store'])->middleware('role_permission:manage:building');
        Route::post('/update/{bd_id}', [BuildingController::class, 'update'])->middleware('role_permission:manage:building');
        Route::post('/updateRatio', [BuildingController::class, 'updateRatio'])->middleware('role_permission:manage:building');
        Route::post('/delete', [BuildingController::class, 'destroy']);
    });





