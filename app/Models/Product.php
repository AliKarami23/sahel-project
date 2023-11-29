<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;

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
    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
