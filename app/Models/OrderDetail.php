<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class OrderDetail extends EloquentModel
{
    use FixingFetchDateTime;
    protected $connection = 'mongodb';
    protected $collection = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_qty',
        'product_price',
        'product_amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
