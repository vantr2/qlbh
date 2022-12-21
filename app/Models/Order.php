<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Order extends EloquentModel
{
    use FixingFetchDateTime;
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    public const IMPORT_MAX_PRODUCT = 20;

    protected $fillable = [
        'customer_id',
        'total',
        'order_date',
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
