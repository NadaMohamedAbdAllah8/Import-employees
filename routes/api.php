<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Admin'],
    function () {
        Route::post('login', [AuthController::class, 'login']);

        Route::group(['middleware' => ['auth:api', 'admin']], function () {
            Route::post('logout', [AuthController::class, 'logout']);

            Route::apiResource('/employees', EmployeeController::class)
                ->except(['store', 'update']);
            Route::post('/employees', [EmployeeController::class, 'import']);
        });

    });
