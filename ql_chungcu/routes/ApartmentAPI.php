<?php

use App\Http\Controllers\ApartmentController;
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
    'prefix' => '/apt'],
    function () {
        Route::get('/findByBuilding/{bd_id}', [ApartmentController::class, 'findByBuildingId'])->middleware('role_permission:view:apartment');
        Route::post('/create', [ApartmentController::class, 'store'])->middleware('role_permission:manage:apartment');
        Route::post('/update/{apt_id}', [ApartmentController::class, 'update'])->middleware('role_permission:manage:apartment');
//        Route::post('/delete', [BuildingController::class, 'destroy']);
        Route::post('import-excel', [ApartmentController::class, 'importAptExcel'])->middleware('role_permission:manage:apartment');
    });





