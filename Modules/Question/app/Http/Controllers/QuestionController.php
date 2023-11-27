<?php

namespace Modules\Question\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Question\app\Models\Question;

class QuestionController extends Controller
{
    public function create_question(Request $request)
    {
        $validateDate = $request->validate([
            'title_question' => 'required|max:255',
            'text_question' => 'required',
        ]);
        $question = Question::create($validateDate);
        return response()->json([
            'massage' => 'سوال جدید با موفقیت ایجاد شد',
            'article' => $question,
        ]);

    }


    public function edit_question(Request $request ,$id)
    {
        $question = Question::find($id);

        $question->update($request->toArray());
        return response()->json([
            'massage' => 'سوال با موفقیت ویرایش شد',
            'question' => $question
        ]);
    }

    public function delete_question($id)
    {
        $question = Question::find($id);
        $question->delete();
        return response()->json([
            'massage' => 'سوال حذف شد',
            'question' => $question
        ]);
    }

    public function list_question()
    {
        $question = Question::all();
        return response()->json($question);
    }

}
