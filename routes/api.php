<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PrefixController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ZipCodeController;
use Illuminate\Support\Facades\Route;

Route::group(
    ['namespace' => 'App\Http\Controllers'],
    function () {
        Route::post('login', [AuthController::class, 'login']);

        Route::group(['middleware' => ['auth:api', 'admin']], function () {
            Route::post('logout', [AuthController::class, 'logout']);

            Route::apiResource('/employees', EmployeeController::class);
            Route::post('/employees/import', [EmployeeController::class, 'import']);

            Route::apiResource('/prefixes', PrefixController::class);

            Route::apiResource('/regions', RegionController::class);

            Route::apiResource('/counties', CountyController::class);

            Route::apiResource('/cities', CityController::class);

            Route::apiResource('/zip-codes', ZipCodeController::class);
        });
    }
);
