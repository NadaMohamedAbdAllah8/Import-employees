<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers'],
    function () {
        Route::post('login', [AuthController::class, 'login']);

        Route::group(['middleware' => ['auth:api', 'admin']], function () {
            Route::post('logout', [AuthController::class, 'logout']);

            Route::apiResource('/employees', EmployeeController::class);
            Route::post('/employees/import', [EmployeeController::class, 'import']);

            Route::apiResource('/regions', RegionController::class);

            Route::apiResource('/prefixes', PrefixController::class);

        });
    });
