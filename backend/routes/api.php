<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductTypeController;
use App\Http\Controllers\Api\MarkController;
use App\Http\Controllers\Api\ProductController;

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

    Route::prefix('marks')->group(function (): void {
        Route::get('/', [MarkController::class, 'index']);
        Route::post('/', [MarkController::class, 'store']);
        Route::get('/{mark}', [MarkController::class, 'show']);
        Route::put('/{mark}', [MarkController::class, 'update']);
        Route::delete('/{mark}', [MarkController::class, 'destroy']);
    });

    Route::prefix('products')->group(function (): void {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });
});
