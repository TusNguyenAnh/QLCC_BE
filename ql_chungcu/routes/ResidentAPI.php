<?php

use App\Http\Controllers\ResidentController;
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

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix' => '/resident'
    ],
    function () {
        Route::post('getByFilter', [ResidentController::class, 'index']);
        Route::get('findByOrgId/{org_id}', [ResidentController::class, 'findByOrgId']);
        Route::post('findByBuildingId', [ResidentController::class, 'findResidentByBuildingId']);
        Route::post('create', [ResidentController::class, 'store'])->middleware('role_permission:manage:resident');
        Route::post('import-excel', [ResidentController::class, 'importResExcel'])->middleware('role_permission:manage:resident');
        Route::post('import-excelAptRes', [ResidentController::class, 'importResAptExcel'])->middleware('role_permission:manage:resident');
        Route::post('addResInOrg/{org_id}', [ResidentController::class, 'addResInOrg'])->middleware('role_permission:manage:resident');
        Route::post('removeResInOrg/{org_id}', [ResidentController::class, 'removeResInOrg'])->middleware('role_permission:manage:resident');
        Route::post('updatePosition', [ResidentController::class, 'updatePosition'])->middleware('role_permission:manage:resident');

    }
);
