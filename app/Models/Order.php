<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'Total_Price',
        'Capacity_Man',
        'Capacity_Woman',
        'Payment_Status',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function reserves()
    {
        return $this->hasMany(Reservation::class);
    }

    public function cards(){

        return $this->hasMany(Card::class);
    }

    public function sans()
    {
        return $this->hasMany(Sans::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }


    protected $casts = [
        "product_id" => "array"
    ];
}
