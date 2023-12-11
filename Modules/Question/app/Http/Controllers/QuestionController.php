<?php

namespace Modules\Question\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Question\app\Models\Question;

class QuestionController extends Controller
{
    public function create(Request $request)
    {

        $question = Question::create($request->all());

        return response()->json([
            'message' => 'Question created successfully',
            'Question' => $question,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $question = Question::find($id);

        $question->update($request->all());

        return response()->json([
            'message' => 'Question edited successfully',
            'Question' => $question,
        ]);
    }

    public function show($id)
    {
        $question = Question::find($id);

        return response()->json([
            'question' => $question,
        ]);
    }

    public function delete($id)
    {
        $question = Question::find($id);
        $question->delete();

        return response()->json([
            'message' => 'The question was deleted',
            'Question' => $question,
        ]);
    }

    public function list()
    {
        $questions = Question::all();

        return response()->json($questions);
    }
}
