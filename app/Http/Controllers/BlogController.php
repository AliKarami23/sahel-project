<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    public function create(Request $request)
    {
        $validateDate = $request->validate([
            'Title_blog' => 'required|max:255',
            'Text_blog' => 'required',
        ]);
        $blog = Blog::create($validateDate);
        return response()->json([
            'massage' => 'نوشته با موفقیت ایجاد شد',
            'blog' => $blog,
        ]);

    }

    public function edit()
    {

    }

    public function delete()
    {

    }

    public function listblog()
    {
        $blogs = Blog::all();
        return response()->json($blogs);
    }
}
