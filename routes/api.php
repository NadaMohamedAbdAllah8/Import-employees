<?php

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

        Route::post('login', 'AuthController@login');

        // protected routes
        Route::group(['middleware' => ['auth:api', 'admin']], function () {
            Route::post('logout', 'AuthController@logout')->name('logout');
        });

    });
