<?php

namespace Modules\Comment\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerCommentRequest;
use App\Http\Requests\CommentRequest;
use App\Models\Product;
use Modules\Comment\app\Models\Comment;

class CommentController extends Controller
{
    public function create(CommentRequest $request)
    {
        $user = auth()->user()->id;
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['product not find']);
        }
        $comment = Comment::create($request->merge(['user_id' => $user])->all());

        return response()->json([
            'message' => 'Comment created successfully.',
            'comment' => $comment
        ]);
    }

    public function answer(AnswerCommentRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update([
            'response' => $request->Response,
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

        if ($comments->isEmpty()) {
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
