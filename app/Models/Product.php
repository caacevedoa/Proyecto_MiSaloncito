<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla (opcional si Laravel lo detecta automáticamente).
     */
    protected $table = 'products';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'product_name',
        'product_type',
        'unit_price',
        'product_status',          // activo / inactivo
    ];
}
