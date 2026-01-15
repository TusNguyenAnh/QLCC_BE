<?php

use App\Http\Controllers\BuildingController;
use App\Http\Controllers\RoleController;
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
//    'middleware' => 'auth:api',
    'prefix' => '/role'],
    function () {
        Route::get('/findByComplexId/{complex_id}', [RoleController::class, 'findByComplexId']);
        Route::post('/getRoleByUserId/{user_id}', [RoleController::class, 'getRoleByUserId']);
        Route::post('/create', [RoleController::class, 'store']);
        Route::post('/assignRole', [RoleController::class, 'assignRole']);

//        Route::post('/update/{bd_id}', [RoleController::class, 'update']);
//        Route::post('/delete', [RoleController::class, 'destroy']);
    });





