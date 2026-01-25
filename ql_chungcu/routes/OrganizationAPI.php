<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Http\Request;
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
    'prefix' => '/org'],
    function () {
        Route::get('', [OrganizationController::class, 'index']);
        Route::get('/findById/{id}', [OrganizationController::class, 'findById']);
        Route::get('/getBdIdByOrgId/{complex_id}', [OrganizationController::class, 'getBdIdByParentOrgId']);
        Route::get('/getTopLevel/{complex_id}', [OrganizationController::class, 'getTopLevel']);
        Route::post('/create', [OrganizationController::class, 'store'])->middleware('role_permission:manage:organization');
        Route::post('/update/{org_id}', [OrganizationController::class, 'update'])->middleware('role_permission:manage:organization');
        Route::post('/delete', [OrganizationController::class, 'destroy']);
    });

Route::get('org/getAllWithoutChild/{parent_org_id}/{complex_id}', [OrganizationController::class, 'getAllWithoutChild']);




