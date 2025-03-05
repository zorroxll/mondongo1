<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Pedido;

class ListoController extends Controller
{
    public function index()
    {
        // Obtener solo pedidos con estado "listo"
        $pedidos = Pedido::with('productos')->where('estado', 'listo')->get();

        foreach ($pedidos as $pedido) {
            foreach ($pedido->productos as $producto) {
                // Consultar API de productos para obtener el nombre
                $response = Http::get(env('API_PRODUCTOS_URL') . '/' . $producto->producto_id);

                if ($response->successful()) {
                    $productoData = $response->json();
                    $producto->nombre = $productoData['nombre'] ?? 'Desconocido';
                } else {
                    $producto->nombre = 'No disponible';
                }

                unset($producto->producto_id);
            }
        }

        return view('mesero.index', compact('pedidos'));
    }
}
