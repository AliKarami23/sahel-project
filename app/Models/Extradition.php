<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extradition extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'extradition',
        'extradition_Time',
        'extradition_Percent',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
