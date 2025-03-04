<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 🟢 Obtener todos los productos
    public function index()
    {
        $productos = Product::all();
        return view('productos', compact('productos')); // Enviar los datos a la vista    
        // Obtener todos los productos
     }

    // 🔵 Crear un nuevo producto
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:products',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $producto = Product::create($request->all());
        return response()->json($producto, 201);
    }

    // 🟡 Mostrar un solo producto
    public function show(Product $producto)
    {
        return response()->json($producto);
    }
    
    // 🟠 Actualizar un producto
    public function update(Request $request, Product $producto)
    {
        $request->validate([
            'nombre' => 'sometimes|required|string|unique:products,nombre,' . $producto->id,
            'descripcion' => 'nullable|string',
            'precio' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $producto->update($request->all());
        return response()->json($producto);
    }

    // 🔴 Eliminar un producto
    public function destroy(Product $producto)
    {
        $producto->delete();
        return response()->json(['message' => 'Producto eliminado']);
    }
}
