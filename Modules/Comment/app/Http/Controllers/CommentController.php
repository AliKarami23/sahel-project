<?php

namespace Modules\Comment\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Comment\app\Models\Comment;

class CommentController extends Controller
{
    public function Create(Request $request){
        $user = auth()->user()->id;

        $Comment = Comment::create($request->merge(['user_id' => $user])->all());

        return response()->json([
            'message' => 'Comment created successfully.',
            'Comment' => $Comment
        ]);
    }

        public function Answer(Request $request,$id){

        $comment = Comment::findOrFail($id);
        $comment->update([
            'Response' => $request->Response,
        ]);
        return response()->json([
            'message' => 'Response to the comment has been successfully recorded.',
            'Comment' => $comment
        ]);
    }

    public function Activate($id){

        $comment = Comment::findOrFail($id);
        $comment->update([
            'Status' => 'Active',
        ]);

        return response()->json([
            'message' => 'Comment has been successfully activated.',
            'Comment' => $comment
        ]);
    }

    public function List(){

        $comments = Comment::all();

        return response()->json([
            'Comment' => $comments
        ]);
    }

    public function Show($id){

        $comments = Comment::where('product_id', $id)
            ->where('Status', 'Active')
            ->get();

        return response()->json([
            'Comment' => $comments
        ]);
    }

    public function Delete($id){

        $comment = Comment::findOrFailOrFail($id);
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.'
        ]);
    }
}
