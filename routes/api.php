<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EmployeeController;
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
Route::group(['prefix' => 'admin', 'namespace' => 'App\Http\Controllers\Admin'],
    function () {

        Route::post('login', [AuthController::class, 'login']);

        Route::group(['middleware' => ['auth:api', 'admin']], function () {
            Route::post('logout', [AuthController::class, 'logout']);

            Route::apiResource('/employees', EmployeeController::class)->except(['store', 'update']);
            Route::post('/employees/import-excel', [EmployeeController::class, 'import']);
        });

    });