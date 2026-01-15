<?php

use App\Http\Controllers\ComplexController;
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
    'prefix' => '/complex',
],
    function () {
        Route::get('/findById/{id}', [ComplexController::class, 'findById']);
        Route::get('/showFinancialModel', [ComplexController::class, 'showFinancialModel']);
        Route::post('/approveComplex', [ComplexController::class, 'approveComplex']);
        Route::post('/rejectComplex', [ComplexController::class, 'rejectComplex']);
    });

Route::post('complex/create', [ComplexController::class, 'store']);
Route::post('complex/filterComplex/{status}', [ComplexController::class, 'filterComplex']);
