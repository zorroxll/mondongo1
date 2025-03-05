<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    // Obtener todas las facturas
    public function index()
    {
        return response()->json(Factura::with('user', 'productos')->get());
    }

    // Crear una nueva factura
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:products,id', // Cambiado de producto_id a id
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);
    
        // Crear la factura con el total calculado
        $factura = Factura::create([
            'user_id' => $request->user_id,
            'total' => collect($request->productos)->sum(fn($p) => $p['cantidad'] * $p['precio_unitario']),
        ]);
    
        // Asociar los productos a la factura
        foreach ($request->productos as $producto) {
            $factura->productos()->attach($producto['id'], [ // Cambiado de producto_id a id
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
            ]);
        }
    
        // Retornar la respuesta con la factura creada
        return response()->json([
            'message' => 'Factura creada',
            'factura' => $factura->load('productos'),
        ], 201);
    }
    

    // Obtener una factura específica
    public function show(Factura $factura)
    {
        return response()->json($factura->load('user', 'productos'));
    }

    // Actualizar una factura
    public function update(Request $request, Factura $factura)
    {
        $request->validate([
            'user_id' => 'exists:users,id',
        ]);

        $factura->update($request->only(['user_id']));

        return response()->json(['message' => 'Factura actualizada', 'factura' => $factura->load('productos')]);
    }

    // Eliminar una factura
    public function destroy(Factura $factura)
    {
        $factura->delete();

        return response()->json(['message' => 'Factura eliminada']);
    }
}
