<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Eloquent implements AuthenticatableContract
{
    use AuthenticableTrait;
    use Notifiable;
    use FixingFetchDateTime;

    const ADMIN = 1;
    const NORMAL_USER = 2;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Customer::class, null, 'user_ids', 'customer_ids');
    }

    public function roleToText()
    {
        $roleText = [self::ADMIN => __('ADMIN'), self::NORMAL_USER => __('NORMAL')];
        return $roleText[$this->role];
    }
}
