<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'Total_Price',
        'Capacity_Man',
        'Capacity_Woman',
        'Payment_Status',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function reserves()
    {
        return $this->hasMany(Reservation::class);
    }

    protected $casts = [
        "product_id" => "array"
    ];
}
