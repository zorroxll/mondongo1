<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use Illuminate\Support\Facades\Http;

class PedidoController extends Controller
{
    public function store(Request $request)
    {
        // Validar datos de entrada
        $request->validate([
            'user_id' => 'required|integer',
            'productos' => 'required|array',
            'productos.*.producto_id' => 'required|integer',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        // 🔥 Verificar si el usuario existe en `mondongo1`
        $userResponse = Http::get(env('API_USUARIOS_URL') . '/' . $request->user_id);

        if ($userResponse->failed()) {
            return response()->json(['error' => 'Usuario no encontrado en monolito'], 404);
        }

        // 🔥 Validar productos en `mondongo1`
        $productosValidos = [];
        $total = 0;

        foreach ($request->productos as $producto) {
            $productoResponse = Http::get(env('API_PRODUCTOS_URL') . '/' . $producto['producto_id']);

            if ($productoResponse->failed() || empty($productoResponse->json()['precio'])) {
                return response()->json([
                    'error' => "Producto ID {$producto['producto_id']} no encontrado en monolito o sin precio válido"
                ], 404);
            }

            $productoData = $productoResponse->json();
            $precio = (float) $productoData['precio'];

            $productosValidos[] = [
                'producto_id' => $producto['producto_id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $precio,
            ];

            $total += $producto['cantidad'] * $precio;
        }

        // Guardar el pedido en `mondongo1_pedidos`
        $pedido = Pedido::create([
            'user_id' => $request->user_id,
            'estado' => 'pendiente',
            'total' => $total,
        ]);

        // Guardar los productos en `PedidoProducto`
        foreach ($productosValidos as $producto) {
            PedidoProducto::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $producto['producto_id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
            ]);
        }

        return response()->json($pedido->load('productos'), 201);
    }

    public function obtenerProductos()
    {
        // Consumir la API de mondongo1 para obtener los productos
        $response = Http::get(env('API_PRODUCTOS_URL'));

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'No se pudieron obtener los productos'], 500);
        }
    }
    public function index()
    {
        $pedidos = Pedido::with('productos')->get();
    
        // Iterar sobre cada pedido para obtener los nombres de los productos
        foreach ($pedidos as $pedido) {
            foreach ($pedido->productos as $producto) {
                // Consultar API de productos
                $response = Http::get(env('API_PRODUCTOS_URL') . '/' . $producto->producto_id);
                
                if ($response->successful()) {
                    $productoData = $response->json();
                    $producto->nombre = $productoData['nombre'] ?? 'Desconocido';
                } else {
                    $producto->nombre = 'No disponible';
                }
    
                // Eliminar el ID para que no se muestre en la respuesta
                unset($producto->producto_id);
            }
        }
    
        return response()->json($pedidos);
    }
    
public function show($id)
{
    $pedido = Pedido::with('productos')->findOrFail($id);

    foreach ($pedido->productos as $producto) {
        $response = Http::get(env('API_PRODUCTOS_URL') . '/' . $producto->producto_id);

        if ($response->successful()) {
            $productoData = $response->json();
            $producto->nombre = $productoData['nombre'] ?? 'Desconocido';
        } else {
            $producto->nombre = 'No disponible';
        }

        unset($producto->producto_id);
    }

    return response()->json($pedido);
}
public function update(Request $request, $id)
{
    $pedido = Pedido::findOrFail($id);
    $pedido->update($request->all());
    return response()->json($pedido);
}
public function destroy($id)
{
    $pedido = Pedido::findOrFail($id);
    $pedido->delete();
    return response()->json(['mensaje' => 'Pedido eliminado']);
}


public function actualizarEstado(Request $request, $id)
{
    $pedido = Pedido::with('productos')->findOrFail($id);

    $request->validate([
        'estado' => 'required|in:pendiente,en preparación,listo,entregado',
    ]);

    $pedido->update(['estado' => $request->estado]);

    // 🚀 Si el pedido se marca como "entregado", enviarlo a facturación
    if ($request->estado === 'entregado') {
        // Log para depuración
        Log::info('Enviando pedido a facturación', ['pedido_id' => $pedido->id]);

        // Construir el JSON para la API de facturación
        $data = [
            'user_id' => $pedido->user_id,
            'productos' => $pedido->productos->map(function ($producto) {
                return [
                    'id' => $producto->producto_id,
                    'cantidad' => $producto->cantidad,
                    'precio_unitario' => $producto->precio_unitario,
                ];
            })->toArray(), // Asegurar que se envía como array
        ];

        // Log del JSON que se enviará
        Log::info('JSON enviado a facturación:', $data);

        $facturacionResponse = Http::post(env('API_FACTURACION_URL') , $data);
        // Registrar la respuesta de la API
        Log::info('Respuesta de API de facturación:', [
            'status' => $facturacionResponse->status(),
            'body' => $facturacionResponse->json()
        ]);

        if ($facturacionResponse->failed()) {
            return response()->json([
                'error' => 'No se pudo enviar a facturación',
                'detalle' => $facturacionResponse->json()
            ], 500);
        }
    }

    return response()->json([
        'mensaje' => 'Estado actualizado correctamente',
        'pedido' => $pedido
    ]);
}



}

