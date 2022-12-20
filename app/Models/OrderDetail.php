<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class OrderDetail extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'order_details';

    protected $fillable = [
        'customer_id',
        'product_id',
        'product_qty',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
