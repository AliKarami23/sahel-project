<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , HasRoles;

    protected $guard_name = 'api';

    protected $fillable = [
        'full_name',
        'phone_number',
        'Email',
        'Password',
        'Status'
    ];


    protected $hidden = [
        'Password',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
