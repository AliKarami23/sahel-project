<?php

namespace Modules\Article\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Video;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Modules\Article\app\Models\Article;

class ArticleController extends Controller
{


    public function Create(Request $request)
    {
        try {
            $video = Video::findOrFail($request->video_id);
            $image = Image::findOrFail($request->image_id);

            $image->update(['Status' => 'Active']);
            $video->update(['Status' => 'Active']);

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

    public function Show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found.'], 404);
        }
        $image_id = $article->image_id;
        if ($image_id){
            $image = Image::find($image_id);
            $article_image = $image->getMedia('*');
        }
        $video_id = $article->video_id;
        if ($video_id){
            $video = video::find($video_id);
            $article_video = $video->getMedia('*');
        }

        return response()->json([
            'article' => $article,
            'article_image' => $article_image,
            'article_video' => $article_video,
        ]);
    }


    public function Edit(Request $request, $id)
    {
        try {
            $video = Video::findOrFail($request->video_id);
            $image = Image::findOrFail($request->image_id);

            $image->update(['Status' => 'Active']);
            $video->update(['Status' => 'Active']);

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


    public function List()
    {
        $articles = Article::select('Title', 'Text')->get();
        return response()->json($articles);
    }


    public function Delete($id)
    {
        try {
            $article = Article::find($id);

            if (!$article) {
                return response()->json(['message' => 'Article not found.'], 404);
            }

            $Image = $article->image_id;

            if ($Image){
                $image_Main = Image::find($Image);
                $image_Main->update(['Status' => 'Inactive']);

            }

            $video_id = $article->video_id;
            if ($video_id){
                $video = video::find($video_id);
                $video->update(['Status' => 'Inactive']);
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
