<?php

namespace Modules\Article\app\Http\Controllers;

use App\Http\Controllers\Controller;
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

    public function UploadImage(Request $request, $id)
    {
        $Article = Article::find($id);
        $Image = $request->file('Image');

        if (isset($Image)) {
            $media = $Article->addMedia($Image)
                ->toMediaCollection('Article_image', 'images');
            $imageUrl = $media->getUrl();
        } else {
            $imageUrl = null;
        }

        return response()->json([
            'Article' => $Article,
            'imageUrl' => $imageUrl,
        ]);
    }



    public function UploadVideo(Request $request, $id)
    {
        $Article = Article::find($id);
        $video = $request->file('video');

        if (isset($video)) {
            $media = $Article->addMedia($video)
                ->toMediaCollection('Article_video', 'videos');

            $videoUrl = $media->getUrl();
        } else {
            $videoUrl = null;
        }

        return response()->json([
            'product' => $Article,
            'video_url' => $videoUrl,
        ]);
    }

}
