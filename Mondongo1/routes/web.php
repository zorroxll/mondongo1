<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FacturaController;

Route::apiResource('productos', ProductController::class);
