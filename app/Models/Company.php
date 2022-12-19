<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Company extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'companies';

    protected $fillable = [
        'name',
        'address',
        'established_year',
        'created_by',
        'updated_by',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
