<?php

use App\Http\Controllers\ResidentController;
use App\Http\Controllers\TaskTypeController;
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

Route::group(['prefix' => '/tt'],
    function () {
        Route::get('/{complex_id}', [TaskTypeController::class, 'index']);
        Route::get('/findByOrgId/{org_id}', [TaskTypeController::class, 'findByOrgId']);
        Route::post('/findByBuildingId', [TaskTypeController::class, 'findResidentByBuildingId']);
        Route::post('/create', [TaskTypeController::class, 'store']);
    });

