<?php

namespace Modules\Contact\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Contact\Database\factories\ContactFactory;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name_contact','email_contact','text_contact'];

    protected static function newFactory(): ContactFactory
    {
        //return ContactFactory::new();
    }
}
