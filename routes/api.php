<?php

use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\LeafletController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\RegionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware(['throttle:api'])->controller(AuthController::class)->group(function () {
    Route::post('register', 'register')->withoutMiddleware('auth:api');
    Route::post('login', 'login')->withoutMiddleware('auth:api');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'logout');
        Route::get('user', 'user');
        Route::post('refresh', 'refresh');
    });
});

Route::middleware(['throttle:api', 'auth:api'])->group(function () {
    Route::apiResources([
        'regions' => RegionController::class,
        'buildings' => BuildingController::class,
        'reactions' => ReactionController::class,
        'apartments' => ApartmentController::class,
        'leaflets' => LeafletController::class,
    ]);
});

Route::patch('distributions/{id}/end', [DistributionController::class, 'end'])->middleware(['throttle:api', 'auth:api'])->name('distributions.end');
Route::apiResource('distributions', DistributionController::class)->middleware(['throttle:api', 'auth:api']);
