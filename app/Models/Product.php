<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'price',
        'discount',
        'discount_amount',
        'discount_type',
        'age_limit',
        'age_limit_value',
        'total_start',
        'total_end',
        'break_time',
        'rules',
        'description',
        'discounted_price',
        'video_id',
        'image_id',
        'image_main_id'
    ];

    protected $casts = [
        "image_id" => "array"
    ];

    public function sans()
    {
        return $this->hasMany(Sans::class);
    }

    public function extraditions()
    {
        return $this->hasMany(Extradition::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getTotalSales()
    {
        return $this->orders()->sum('Total_Price');
    }

    public function updateTicketsSold()
    {
        $totalSoldMan = Reservation::where('product_id', $this->id)->sum('Tickets_Sold_Man');
        $totalSoldWoman = Reservation::where('product_id', $this->id)->sum('Tickets_Sold_Woman');

        return [
            'totalSoldMan' => $totalSoldMan,
            'totalSoldWoman' => $totalSoldWoman,
        ];
    }


}
