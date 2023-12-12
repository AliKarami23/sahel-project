<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Http\Requests\VideoRequest;
use App\Models\Image;
use App\Models\Video;

class MediaController extends Controller
{
    public function uploadImage(ImageRequest $request)
    {
        $image = new Image();
        $image->addMediaFromRequest('image')->toMediaCollection('image', 'images');
        $image->save();

        return response()->json($image);
    }

    public function uploadMainImage(ImageRequest $request)
    {
        $image = new Image();
        $image->addMediaFromRequest('image')->toMediaCollection('image_Main', 'images');
        $image->save();

        return response()->json($image);
    }

    public function uploadVideo(VideoRequest $request)
    {
        $video = new Video();
        $video->addMediaFromRequest('video')->toMediaCollection('videos', 'videos');
        $video->save();

        return response()->json($video);
    }
}
