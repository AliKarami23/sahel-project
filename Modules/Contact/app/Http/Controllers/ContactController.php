<?php

namespace Modules\Contact\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Contact\App\Http\Requests\AnswerContactRequest;
use Modules\Contact\App\Http\Requests\ContactRequest;
use Modules\Contact\app\Models\Contact;
use App\Mail\AnswerContactMail;

class ContactController extends Controller
{
    public function create(ContactRequest $request)
    {
        $contact = Contact::create($request->all());

        return response()->json([
            'message' => 'Your message has been received',
            'contact' => $contact,
        ]);
    }

    public function list()
    {
        $contacts = Contact::all();

        return response()->json($contacts);
    }

    public function show($id)
    {
        $contact = Contact::find($id);

        return response()->json([
            'contact' => $contact
        ]);
    }

    public function answer(AnswerContactRequest $request, $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'message' => 'Contact not found',
            ], 404);
        }

        $contact->update([
            'answer' => $request->answer
        ]);

        $email = $contact->email;
        $answer = $request->answer;

        Mail::to($email)->send(new AnswerContactMail($answer));

        return response()->json([
            'answer' => $answer,
            'message' => 'Reply sent successfully',
        ]);
    }

    public function delete($id)
    {
        Contact::delete($id);

        return response()->json([
            'message' => 'The desired message was deleted'
        ]);
    }
}
