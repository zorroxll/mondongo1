<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock'];
    public function facturas()
{
    return $this->belongsToMany(Factura::class, 'factura_productos', 'producto_id', 'factura_id')
                ->withPivot('cantidad', 'precio_unitario')
                ->withTimestamps();
}

}
