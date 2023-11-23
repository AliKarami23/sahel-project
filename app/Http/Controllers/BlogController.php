<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    public function create()
    {
        $validateDate = $request->validate([
            'Title_Blog' => 'required = max :255',
            'Text_Blog' => 'required',
        ]);
        $blog =Blog::create($validateDate);
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

    public function list()
    {
        $blogs = Blog::all();
        return response()->json($blogs);
    }
}
