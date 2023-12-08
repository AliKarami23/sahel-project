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

    public function Create(Request $request)
    {

        $contact = Contact::create($request->all());
        return response()->json([
            'massage' => 'Your message has been received',
            'contact' => $contact,
        ]);

    }

    public function List()
    {
        $contact = Contact::all();
        return response()->json($contact);
    }

    public function Show($id)
    {
        $Contact = Contact::find($id);
        return response()->json([
            'Contact' => $Contact
        ]);
    }

    public function Answer(Request $request, $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'message' => 'Contact not found',
            ], 404);
        }

        $contact->update([
            'Answer' => $request->Answer
        ]);

        $Email = $contact->Email;
        $Answer = $request->Answer;

        Mail::to($Email)->send(new AnswerContactMail($Answer));

        return response()->json([
            'Answer' => $Answer,
            'message' => 'Reply sent successfully',
        ]);
    }


    public function Delete($id)
    {
        Contact::distroy($id);
        return response()->json([
            'massage' => 'The desired message was deleted'
        ]);

    }
}
