<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Customer extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'customers';

    // Gender option
    public const MALE = 1;
    public const FEMALE = 2;
    public const OTHER = 3;


    // Type option
    public const VIP = 1;
    public const NORMAL = 2;

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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
