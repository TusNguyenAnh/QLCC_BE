<?php

use App\Http\Controllers\PriorityController;
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
    'prefix' => '/priority'],
    function () {
        Route::get('', [PriorityController::class, 'index']);
    });





