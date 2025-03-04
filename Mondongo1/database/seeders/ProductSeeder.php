<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::insert([
            ['nombre' => 'Pizza Margarita', 'descripcion' => 'Pizza con tomate, mozzarella y albahaca.', 'precio' => 10.99, 'stock' => 20],
            ['nombre' => 'Hamburguesa Clásica', 'descripcion' => 'Carne de res, lechuga, tomate y queso.', 'precio' => 8.50, 'stock' => 15],
            ['nombre' => 'Papas Fritas', 'descripcion' => 'Papas fritas crujientes.', 'precio' => 3.99, 'stock' => 30],
        ]);
    }
}
