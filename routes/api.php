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

Route::middleware(['auth:api', 'throttle:api'])->group(function () {
    Route::apiResources([
        'regions' => RegionController::class,
        'buildings' => BuildingController::class,
        'reactions' => ReactionController::class,
        'leaflets' => LeafletController::class,
    ]);
});

Route::middleware(['auth:api', 'throttle:api'])->group(function () {
    Route::apiResource('apartments', ApartmentController::class);

    Route::patch('apartments/{apartment}/react', [ApartmentController::class, 'react'])
        ->name('apartments.react');
});

Route::middleware(['auth:api', 'throttle:api'])->group(function () {
    Route::post('distributions/began', [DistributionController::class, 'began'])
        ->name('distributions.began');
    Route::get('distributions/current', [DistributionController::class, 'current'])
        ->name('distributions.current');
    Route::patch('distributions/{distribution}/end', [DistributionController::class, 'end'])
        ->name('distributions.end');

    Route::apiResource('distributions', DistributionController::class)
        ->except(['store']);
});
