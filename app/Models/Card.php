<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'card_number',
        'card_link'
    ];

    public function order(){

        return $this->belongsTo(Order::class);

    }
}
