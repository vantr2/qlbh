<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Customer extends EloquentModel
{
    use FixingFetchDateTime;

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
        'created_by',
        'updated_by',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Type to text
     *
     * @return string
     */
    public function typeToText()
    {
        $typeToText = [Customer::VIP => __('VIP'), Customer::NORMAL => __('Normal')];
        return $this->type ? $typeToText[intval($this->type)] : '';
    }

    /**
     * Gender to text
     *
     * @return string
     */
    public function genderToText()
    {
        $genderToText = [
            Customer::MALE => __('Male'),
            Customer::FEMALE => __('Female'),
            Customer::OTHER => __('Other')
        ];
        return $this->gender ? $genderToText[intval($this->gender)] : '';
    }

    public function beApplied()
    {
        return $this->belongsToMany(User::class, null, 'customer_ids', 'user_ids');
    }
}
