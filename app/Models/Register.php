<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Register extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'number',
        'Email',
        'verification_code'
    ];

}
