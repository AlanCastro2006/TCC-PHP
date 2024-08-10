<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $details = [
            'email' => $request->email,
            'message' => $request->message,
        ];

        Mail::to('alanmoreiraduart@gmail.com')->send(new \App\Mail\ContactMail($details));

        return back()->with('success', 'Sua mensagem foi enviada com sucesso!');
    }
}
