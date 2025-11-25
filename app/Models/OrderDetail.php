<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'order_details';

    // Campos asignables
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    /**
     * Relación: un detalle pertenece a una orden.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Relación: un detalle pertenece a un producto.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
