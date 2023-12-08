<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Video;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function UploadImage(Request $request)
    {
        $image = new Image();
        $image->addMediaFromRequest('image')->toMediaCollection('image', 'images');
        $image->save();

        return response()->json($image);
    }

    public function UploadMainImage(Request $request)
    {
        $image = new Image();
        $image->addMediaFromRequest('image')->toMediaCollection('image_Main', 'images');
        $image->save();

        return response()->json($image);
    }

    public function UploadVideo(Request $request)
    {
        $video = new Video();
        $video->addMediaFromRequest('video')->toMediaCollection('videos', 'videos');
        $video->save();

        return response()->json($video);
    }
}
