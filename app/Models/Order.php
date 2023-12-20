<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'payment_status',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }


    public function reserves()
    {
        return $this->hasMany(Reservation::class);
    }

    public function card()
    {
        return $this->hasOne(Card::class);
    }

    public function sans()
    {
        return $this->hasMany(Sans::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function extradition()
    {
        return $this->hasMany(Extradition::class);
    }

    protected $casts = [
        "product_id" => "array"
    ];
}
