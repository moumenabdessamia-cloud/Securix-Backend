<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupplierController;

// ... autres routes
Route::get('/suppliers', [SupplierController::class, 'index']);
Route::post('/suppliers', [SupplierController::class, 'store']);
Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy']);

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/admin/register', [AuthController::class, 'adminRegister']);

// Produits publics
Route::get('/products/featured',       [ProductController::class, 'featured']);
Route::get('/products/on-sale',        [ProductController::class, 'onSale']);
Route::get('/products/category/{id}',  [ProductController::class, 'byCategory']);
Route::get('/products',                [ProductController::class, 'index']);
Route::get('/products/{id}',           [ProductController::class, 'show']);

// Stats publiques
Route::get('/stats', [ProductController::class, 'stats']);

// Catégories publiques
Route::get('/categories', [CategoryController::class, 'index']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::put('/profile',  [AuthController::class, 'updateProfile']);

    Route::post('/products',        [ProductController::class, 'store']);
    Route::put('/products/{id}',    [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::post('/categories',        [CategoryController::class, 'store']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    Route::post('/orders',              [OrderController::class, 'store']);
    Route::get('/orders',               [OrderController::class, 'index']);
    Route::put('/orders/{id}/status',   [OrderController::class, 'updateStatus']);
});