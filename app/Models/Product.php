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
