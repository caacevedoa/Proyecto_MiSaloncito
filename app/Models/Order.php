<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Campos que se pueden llenar con create() o update()
    protected $fillable = [
        'order_datetime',
        'status',
        'user_id',
        'table_id',
        'table_status',
        'total',
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con la mesa
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    // Relación con los detalles
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    // Alias alternativo (si tu código lo usa)
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    // Relación con pago (1 a 1)
    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'order_id');
    }

    /**
     * Calcula el total de la orden usando (cantidad * precio_unitario)
     * y actualiza el campo total en la DB.
     */
    public function calculateTotal()
    {
        // Asegura que los detalles estén cargados
        $this->loadMissing('details');

        // Suma el subtotal de cada detalle
        $newTotal = $this->details->sum(function ($detail) {
            return $detail->quantity * $detail->unit_price;
        });

        // Guarda el nuevo total
        $this->total = $newTotal;
        $this->save();

        return $newTotal;
    }
    protected $dates = ['order_datetime'];
}
