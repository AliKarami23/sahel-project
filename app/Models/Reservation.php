<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sans_id',
        'order_id',
        'product_id',
        'Tickets_Sold_Man',
        'Tickets_Sold_Woman',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
