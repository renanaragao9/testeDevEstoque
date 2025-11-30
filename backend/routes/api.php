<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductTypeController;

Route::prefix('auth')->group(function (): void {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('register', [AuthController::class, 'register']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::prefix('product-types')->group(function (): void {
        Route::get('/', [ProductTypeController::class, 'index']);
        Route::post('/', [ProductTypeController::class, 'store']);
        Route::get('/{product_type}', [ProductTypeController::class, 'show']);
        Route::put('/{product_type}', [ProductTypeController::class, 'update']);
        Route::delete('/{product_type}', [ProductTypeController::class, 'destroy']);
    });
});
