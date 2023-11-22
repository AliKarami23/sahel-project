<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class PhoneNumber extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'phone_numbers';
    protected $fillable = ['number', 'verification_code'];

}
