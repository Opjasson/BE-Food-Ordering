<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'table_no',
        'order_date',
        'order_time',
        'status',
        'total',
        'waiterss_id'
    ];

    public function sumOrderPrice() {
        $orderDetail = OrderDetail::where('order_id', $this->id)->pluck('price');
        $sumOrdersPrice = collect($orderDetail)->sum();

        return $sumOrdersPrice;
    }
}
