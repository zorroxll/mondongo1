<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends ApiProductController
{
    // 🟢 Obtener todos los productos
    public function index()
    {
        $productos = Product::all();
        return view('productos', compact('productos')); // Enviar los datos a la vista
    }
}

