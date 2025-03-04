<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\Api\UserController;
//rutas user
Route::get('/users/{id}', [UserController::class, 'show']);

// Rutas de productos
Route::apiResource('productos', ProductController::class);

// Rutas de facturas
Route::apiResource('facturas', FacturaController::class);