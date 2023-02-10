<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Product extends EloquentModel
{
    use FixingFetchDateTime;
    use HasFactory;

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

    public function scopeNameAsc($query)
    {
        $query->orderBy('name', 'asc');
    }
}
