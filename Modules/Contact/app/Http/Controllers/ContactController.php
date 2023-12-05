<?php

namespace Modules\Contact\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Modules\Contact\app\Models\Contact;
use App\Mail\AnswerContactMail;

class ContactController extends Controller
{

    public function create_contact(Request $request)
    {
        $validateDate = $request->validate([
            'name_contact' => 'required',
            'email_contact' => 'required',
            'text_contact' => 'required'
        ]);

        $contact = Contact::create($validateDate);
        return response()->json([
            'massage' => 'پیام شما دریافت شد',
            'contact' => $contact,
        ]);

    }

    public function list_contact()
    {
        $contact = Contact::all();
        return response()->json($contact);
    }

    public function show_answer_contact($id)
    {
        $contact = Contact::find($id);
        return response()->json([
            'date' => $contact
        ]);
    }

    public function answer_contact(Request $request, $id)
    {
        $contact = Contact::find($id);

        $validatedData = $request->validate([
            'answer_text' => 'required'
        ]);


        $contact->answer_text = $validatedData['answer_text'];
        $contact->save();
//dd($contact->answer_text);
        $to_user = $contact->email_contact;
        $subject = 'پاسخ به سوال شما';
        $message = ':پاسخ شما' . $validatedData['answer_text'];
        Mail::send(new \App\Mail\AnswerContactMail($subject, $message, $to_user));
        return response()->json([
            'mail' => $message,
            'message' => 'پاسخ با موفقیت ارسال شد',
        ]);
    }


    public function delete_contact($id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        return response()->json([
            'massage' => 'پیام مورد نظر خذف شد'
        ]);

    }
}
