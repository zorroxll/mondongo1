<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'estado', 'total'];

    // Relación con los productos (usando tabla intermedia)
    public function productos()
    {
        return $this->hasMany(PedidoProducto::class);
    }
}
