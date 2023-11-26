<?php

namespace Modules\Article\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Article\app\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('article::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_article(Request $request)
    {
        $validateDate = $request->validate([
            'title_article' => 'required|max:255',
            'text_article' => 'required',
        ]);
        $article = Article::create($validateDate);
        return response()->json([
            'massage' => 'مثاله با موفقیت ایجاد شد',
            'article' => $article,
        ]);

    }

    public function edit_article(Request $request ,$id)
    {
     $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'مقاله پیدا نشد.'], 404);
        }
     $article->update($request->toArray());
     return response()->json([
         'massage' => 'مقاله با موفقیت ویرایش شد',
         'article' => $article
     ]);
    }

    public function delete_article($id)
    {
        // یافتن مقاله با استفاده از شناسه
        $article = Article::find($id);

        // بررسی آیا مقاله یافت شده است یا خیر
        if (!$article) {
            return response()->json(['message' => 'مقاله پیدا نشد.'], 404);
        }

        // حذف مقاله
        $article->delete();

        // بازگشت پاسخ JSON
        return response()->json(['message' => 'مقاله با موفقیت حذف شد']);
    }

    public function list_article()
    {
        $articles = Article::all();
        return response()->json($articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
