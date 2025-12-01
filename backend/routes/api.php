<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductTypeController;
use App\Http\Controllers\Api\MarkController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SpecificationController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SaleController;

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
        Route::get('/products-sales', [ProductController::class, 'productsSales']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });

    Route::prefix('specifications')->group(function (): void {
        Route::get('/', [SpecificationController::class, 'index']);
        Route::post('/', [SpecificationController::class, 'store']);
        Route::get('/{specification}', [SpecificationController::class, 'show']);
        Route::put('/{specification}', [SpecificationController::class, 'update']);
        Route::delete('/{specification}', [SpecificationController::class, 'destroy']);
    });

    Route::prefix('warehouses')->group(function (): void {
        Route::get('/', [WarehouseController::class, 'index']);
        Route::post('/', [WarehouseController::class, 'store']);
        Route::get('/{warehouse}', [WarehouseController::class, 'show']);
        Route::put('/{warehouse}', [WarehouseController::class, 'update']);
        Route::delete('/{warehouse}', [WarehouseController::class, 'destroy']);
    });

    Route::prefix('stocks')->group(function (): void {
        Route::get('/', [StockController::class, 'index']);
        Route::post('/', [StockController::class, 'store']);
        Route::get('/{stock}', [StockController::class, 'show']);
        Route::put('/{stock}', [StockController::class, 'update']);
        Route::delete('/{stock}', [StockController::class, 'destroy']);
    });

    Route::prefix('suppliers')->group(function (): void {
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::get('/{supplier}', [SupplierController::class, 'show']);
        Route::put('/{supplier}', [SupplierController::class, 'update']);
        Route::delete('/{supplier}', [SupplierController::class, 'destroy']);
    });

    Route::prefix('purchases')->group(function (): void {
        Route::get('/', [PurchaseController::class, 'index']);
        Route::post('/', [PurchaseController::class, 'store']);
        Route::get('/{purchase}', [PurchaseController::class, 'show']);
        Route::delete('/{purchase}', [PurchaseController::class, 'destroy']);
    });

    Route::prefix('customers')->group(function (): void {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('/{customer}', [CustomerController::class, 'show']);
        Route::put('/{customer}', [CustomerController::class, 'update']);
        Route::delete('/{customer}', [CustomerController::class, 'destroy']);
    });

    Route::prefix('sales')->group(function (): void {
        Route::get('/', [SaleController::class, 'index']);
        Route::post('/', [SaleController::class, 'store']);
        Route::get('/{sale}', [SaleController::class, 'show']);
        Route::delete('/{sale}', [SaleController::class, 'destroy']);
    });
});
