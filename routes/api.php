<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MarketPlaceController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post(uri: '/register', action: [AuthController::class, 'register']);
    Route::post(uri: '/login', action: [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post(uri: '/logout', action: [AuthController::class, 'logout']);

    Route::prefix('/team')->group(function () {
        Route::get('/', [TeamController::class, 'show']);
        Route::put('/', [TeamController::class, 'update']);
    });

    Route::prefix('/player')->group(function () {
        Route::put('/{player}', [PlayerController::class, 'update']);
        Route::post('/{player}/marketplace', [PlayerController::class, 'putPlayerOnMarketplace']);
    });

    Route::prefix('/marketplace')->group(function () {
        Route::get('/', [MarketPlaceController::class, 'index']);
        Route::post('/{marketplace}/buy', [MarketPlaceController::class, 'buyPlayer']);
    });
});
