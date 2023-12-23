<?php

namespace Modules\Comment\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Comment\app\Models\Comment;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $user = auth()->user()->id;

        $comment = Comment::create($request->merge(['user_id' => $user])->all());

        return response()->json([
            'message' => 'Comment created successfully.',
            'comment' => $comment
        ]);
    }

    public function answer(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update([
            'response' => $request->response,
        ]);

        return response()->json([
            'message' => 'Response to the comment has been successfully recorded.',
            'comment' => $comment
        ]);
    }

    public function activate($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update([
            'status' => 'Active',
        ]);

        return response()->json([
            'message' => 'Comment has been successfully activated.',
            'comment' => $comment
        ]);
    }

    public function list()
    {
        $comments = Comment::all();

        return response()->json([
            'comment' => $comments
        ]);
    }

    public function show($id)
    {
        $comments = Comment::where('product_id', $id)
            ->where('status', 'Active')
            ->get();

        if ($comments->isEmpty()){
            return response()->json(['message' => 'comments not found']);
        }

        return response()->json([
            'comment' => $comments
        ]);
    }


    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.',
        ]);
    }
}
