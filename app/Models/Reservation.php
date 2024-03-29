<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sans_id',
        'order_id',
        'product_id',
        'tickets_sold_man',
        'tickets_sold_woman',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sans()
    {
        return $this->belongsTo(Sans::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'order_id');
    }

    public function extradition()
    {
        return $this->hasMany(Extradition::class);
    }
}
