<?php

namespace Modules\Comment\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'score',
        'massage',
        'status',
        'response'
    ];
}
