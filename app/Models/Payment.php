<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'track_id',
        'id',
        'order_id',
        'amount',
        'card_no',
        'hashed_card_no',
        'date',
    ];
}
