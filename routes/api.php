<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api', 'prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::post('register', 'register')->withoutMiddleware('auth:api');
    Route::post('login', 'login')->withoutMiddleware('auth:api');
    Route::post('logout', 'logout');
    Route::get('user', 'user');
    Route::post('refresh', 'refresh');
});
