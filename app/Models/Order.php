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
    ];

    // Relación con el usuario (muchas órdenes pertenecen a un usuario)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con la mesa (muchas órdenes pertenecen a una mesa)
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function details()
{
    // Relación con los detalles de la orden
    return $this->hasMany(OrderDetail::class, 'order_id');
}

/**
 * Calcula el total de la orden usando (cantidad * precio_unitario) y lo guarda en la base de datos.
 */
public function calculateTotal()
{
    // Carga la relación 'details' si no está cargada
    $this->loadMissing('details'); 

    // Calcula el total iterando sobre los detalles
    // Función de callback (Closure) que realiza: Quantity * Unit_Price
    $newTotal = $this->details->sum(function ($detail) {
        // Asegúrate de que 'unit_price' y 'quantity' existan en el modelo OrderDetail
        return $detail->quantity * $detail->unit_price; 
    });

    // Actualiza la columna 'total' y guarda
    $this->total = $newTotal;
    $this->save(); 
    
    return $newTotal;
}
}
