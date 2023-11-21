<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sans extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'Start',
        'End',
        'Date',
        'Status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
