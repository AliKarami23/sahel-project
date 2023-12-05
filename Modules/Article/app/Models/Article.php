<?php

namespace Modules\Article\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Article extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = ['Title', 'Text'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->singleFile();
        $this->addMediaCollection('videos')->singleFile();
    }
}

