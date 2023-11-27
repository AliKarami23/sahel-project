<?php

namespace Modules\Contact\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Contact\app\Models\Contact;

class ContactController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create_contact(Request $request)
    {
        $validateDate = $request->validate([
            'name_contact'=>'required',
            'email_contact'=> 'required',
            'text_contact'=>'required'
        ]);

        $contact = Contact::create($validateDate);
        return response()->json([
            'massage'=>'پیام شما دریافت شد',
            'contact'=> $contact,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('contact::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('contact::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
