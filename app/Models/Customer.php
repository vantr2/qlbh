<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Customer extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'customers';

    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'gender',
        'birthday',
        'address',
        'type',
        'company_id',
    ];
}
