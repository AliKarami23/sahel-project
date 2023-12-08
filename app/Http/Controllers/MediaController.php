<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Video;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function UploadImage(Request $request)
    {
        try {
            $image = new Image();
            $image->addMediaFromRequest('image')->toMediaCollection('image', 'images');
            $image->save();

            return response()->json($image);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function UploadMainImage(Request $request)
    {
        try {
            $image = new Image();
            $image->addMediaFromRequest('image')->toMediaCollection('image_Main', 'images');
            $image->save();

            return response()->json($image);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function UploadVideo(Request $request)
    {
        try {
            $video = new Video();
            $video->addMediaFromRequest('video')->toMediaCollection('videos', 'videos');
            $video->save();

            return response()->json($video);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
