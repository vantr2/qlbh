<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Eloquent implements AuthenticatableContract
{
    use AuthenticableTrait;
    use Notifiable;
    use FixingFetchDateTime;
    use HasFactory;

    const SUPER_ADMIN = -1;
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
        'avatar',
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

    public function scopeNameAsc($query)
    {
        $query->orderBy('name', 'asc');
    }

    public function roleToText()
    {
        $roleText = [self::ADMIN => __('ADMIN'), self::NORMAL_USER => __('NORMAL'), self::SUPER_ADMIN => __('SUPER_ADMIN')];
        return $roleText[$this->role];
    }

    public function isAdmin()
    {
        return $this->role == self::ADMIN || $this->role == self::SUPER_ADMIN;
    }

    public function isSuperAdmin()
    {
        return $this->role == self::SUPER_ADMIN;
    }
}
