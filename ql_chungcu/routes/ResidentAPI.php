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

Route::group(['prefix' => '/resident'],
    function () {
        Route::get('', [ResidentController::class, 'index']);
        Route::get('findByOrgId/{org_id}', [ResidentController::class, 'findByOrgId']);
        Route::post('findByBuildingId', [ResidentController::class, 'findResidentByBuildingId']);
        Route::post('create', [ResidentController::class, 'store']);
        Route::post('/updateResInOrg/{org_id}', [ResidentController::class, 'updateResInOrg']);
    });

