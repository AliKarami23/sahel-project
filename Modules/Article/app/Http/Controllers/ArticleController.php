<?php

namespace Modules\Article\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Modules\Article\app\Models\Article;

class ArticleController extends Controller
{


    public function Create(Request $request)
    {
        $article = Article::create($request->all());
        return response()->json([
            'message' => 'Article created successfully',
            'article' => $article,
        ]);

    }

    public function Show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found.'], 404);
        }

        $mediaItems = $article->getMedia();

        return response()->json([
            'article' => $article,
            'mediaItems' => $mediaItems
        ]);
    }


    public function Edit(Request $request ,$id)
    {
     $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found.'], 404);
        }
     $article->update($request->toArray());
     return response()->json([
         'message' => 'Article updated successfully',
         'article' => $article
     ]);
    }


    public function List()
    {
        $articles = Article::all();
        return response()->json($articles);
    }


    public function Delete($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found.'], 404);
        }

        $article->delete();
        return response()->json(['message' => 'The article has been successfully deleted']);
    }

    public function UploadImage(Request $request)
    {
        $imageFile = $request->file('Image');

        if ($request->hasFile('Image') && $imageFile->isValid()) {
            $media->addMedia($imageFile)->toMediaCollection('Article_image', 'images');
        }

        return response()->json([
            'Media' => $media,
        ]);
    }

    public function UploadVideo(Request $request, $id)
    {
        $videoFile = $request->file('video');
        $media = new Media();

        if ($request->hasFile('video') && $videoFile->isValid()) {
            $media->addMedia($videoFile)->toMediaCollection('Article_video', 'videos');
        }

        return response()->json([
            'Media' => $media,
        ]);
    }
}
