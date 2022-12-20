<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Order extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'customer_id',
        'total',
        'date_created',
        'created_by',
        'updated_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
