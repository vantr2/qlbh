<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Product extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'created_by',
        'updated_by',
    ];

    public function inOrder()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
