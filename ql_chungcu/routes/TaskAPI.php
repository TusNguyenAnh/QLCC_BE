<?php

use App\Http\Controllers\ResidentController;
use App\Http\Controllers\TaskController;
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

Route::group(['prefix' => '/task'],
    function () {
        Route::get('/taskActionSummary', [TaskController::class, 'taskActionSummary']);
        Route::get('findByOrgId/{task_status}/{org_id}', [TaskController::class, 'findByOrgId']);
        Route::get('findWfByTaskId/{task_id}', [TaskController::class, 'findWfByTaskId']);
        Route::post('create', [TaskController::class, 'store']);
        Route::post('/approveTask/{task_id}', [TaskController::class, 'approveTask']);
        Route::post('/rejectTask/{task_id}', [TaskController::class, 'rejectTask']);
    });

