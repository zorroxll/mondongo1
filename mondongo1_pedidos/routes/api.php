<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
//rutas user

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/users/{id}', [UserController::class, 'getUser']);

Route::apiResource('pedidos', PedidoController::class);
Route::put('/pedidos/{id}/estado', [PedidoController::class, 'actualizarEstado']);
