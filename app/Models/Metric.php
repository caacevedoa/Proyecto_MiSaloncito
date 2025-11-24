<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    protected $fillable = [
        'pay_id',
        'user_id',
        'order_id',
        'record_date',
        'total_sales_date',
        'total_orders',
        'best_selling_product_id',
        'most_active_user_id',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'pay_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
