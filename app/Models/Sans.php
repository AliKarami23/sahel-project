<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sans extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'start',
        'end',
        'date',
        'status',
        'capacity_man',
        'capacity_woman',
        'capacity_remains_man',
        'capacity_remains_woman',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reserves()
    {
        return $this->belongsTo(Reservation::class);
    }

}
