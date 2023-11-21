<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'Title',
        'Price',
        'Discount',
        'Discount_Amount',
        'Discount_Type',
        'Age_Limit',
        'Age_Limit_Value',
        'Total_Start',
        'Total_End',
        'Break_Time',
        'Capacity_Men',
        'Capacity_Women',
        'Capacity_Total',
        'Rules',
        'Description',
        'Discounted_price',
    ];

    public function sans()
    {
        return $this->hasMany(Sans::class);
    }

    public function extraditions()
    {
        return $this->hasMany(Extradition::class);
    }

}
