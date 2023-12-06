<?php

namespace Modules\Question\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Question\app\Models\Question;

class QuestionController extends Controller
{
    public function Create(Request $request)
    {
        $validateDate = $request->validate([
            'Title' => 'required|max:255',
            'Text' => 'required',
        ]);
        $question = Question::create($validateDate);
        return response()->json([
            'massage' => 'Question created successfully',
            'Question' => $question,
        ]);

    }


    public function Edit(Request $request ,$id)
    {
        $question = Question::find($id);

        $question->update($request->toArray());
        return response()->json([
            'massage' => 'Question edited successfully',
            'Question' => $question
        ]);
    }

    public function Show($id)
    {
        $question = Question::find($id);

        return response()->json([
            'question' => $question
        ]);
    }

    public function Delete($id)
    {
        $question = Question::find($id);
        $question->delete();
        return response()->json([
            'massage' => 'The question was deleted',
            'Question' => $question
        ]);
    }

    public function List()
    {
        $question = Question::all();
        return response()->json($question);
    }

}
