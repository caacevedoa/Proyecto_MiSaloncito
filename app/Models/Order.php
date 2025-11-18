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
}
