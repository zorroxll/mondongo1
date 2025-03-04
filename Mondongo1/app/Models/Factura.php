<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Product::class, 'factura_productos', 'factura_id', 'producto_id')
                    ->withPivot('cantidad', 'precio_unitario')
                    ->withTimestamps();
    }
}
