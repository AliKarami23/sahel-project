<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extradition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reserve_id',
        'order_id',
        'status',
        'price',
        'card_number',
        'name_card',
        'capacity_man',
        'capacity_woman',
        'answer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function reserve()
    {
        return $this->belongsTo(Reservation::class);
    }
}
