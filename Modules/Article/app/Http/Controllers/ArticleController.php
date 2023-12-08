<?php

namespace Modules\Article\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Http\Request;
use Modules\Article\app\Models\Article;

class ArticleController extends Controller
{
    public function create(Request $request)
    {
        try {
            $video = Video::findOrFail($request->video_id);
            $image = Image::findOrFail($request->image_id);

            $image->update(['status' => 'Active']);
            $video->update(['status' => 'Active']);

            $article = Article::create($request->all());

            return response()->json([
                'message' => 'Article created successfully',
                'article' => $article,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error creating article',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found.'], 404);
        }

        $articleImage = $article->image_id ? Image::find($article->image_id)->getMedia('*') : null;
        $articleVideo = $article->video_id ? Video::find($article->video_id)->getMedia('*') : null;

        return response()->json([
            'article' => $article,
            'article_image' => $articleImage,
            'article_video' => $articleVideo,
        ]);
    }

    public function edit(Request $request, $id)
    {
        try {
            $video = Video::findOrFail($request->video_id);
            $image = Image::findOrFail($request->image_id);

            $image->update(['status' => 'Active']);
            $video->update(['status' => 'Active']);

            $article = Article::findOrFail($id);
            $article->update($request->all());

            return response()->json([
                'message' => 'Article updated successfully',
                'article' => $article
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error updating article',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function list()
    {
        $articles = Article::select('title', 'text')->get();
        return response()->json($articles);
    }

    public function destroy($id)
    {
        try {
            $article = Article::find($id);

            if (!$article) {
                return response()->json(['message' => 'Article not found.'], 404);
            }

            $imageId = $article->image_id;
            if ($imageId) {
                $image = Image::find($imageId);
                $image->update(['status' => 'Inactive']);
            }

            $videoId = $article->video_id;
            if ($videoId) {
                $video = Video::find($videoId);
                $video->update(['status' => 'Inactive']);
            }

            $article->delete();

            return response()->json(['message' => 'The article has been successfully deleted']);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error deleting article',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
