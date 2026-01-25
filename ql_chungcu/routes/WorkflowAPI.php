<?php

use App\Http\Controllers\WorkflowController;
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
    'prefix' => '/wf'],
    function () {
        Route::get('/{complex_id}', [WorkflowController::class, 'index']);
        Route::post('/create', [WorkflowController::class, 'store'])->middleware('role_permission:manage:workflow');
        Route::post('/update/{bd_id}', [WorkflowController::class, 'update'])->middleware('role_permission:manage:workflow');
        Route::post('/delete', [WorkflowController::class, 'destroy'])->middleware('role_permission:manage:workflow');
    });





