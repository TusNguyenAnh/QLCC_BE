<?php

use App\Http\Controllers\BuildingController;
use App\Http\Controllers\PermissionController;
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
    'prefix' => '/permission'],
    function () {
        Route::get('', [PermissionController::class, 'getAllRole']);
        Route::post('/create', [PermissionController::class, 'store']);
        Route::post('/assignPermission', [PermissionController::class, 'assignPermission']);

//        Route::post('/update/{bd_id}', [BuildingController::class, 'update']);
//        Route::post('/delete', [BuildingController::class, 'destroy']);
    });





