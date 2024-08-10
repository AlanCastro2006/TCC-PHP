<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    /**
     * Envia um e-mail com os detalhes do formulário de contato.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        // Valida os dados do formulário
        $request->validate([
            'email' => 'required|email',  // O campo 'email' deve ser preenchido e ter um formato de e-mail válido
            'message' => 'required',  // O campo 'message' deve ser preenchido
        ]);

        // Prepara os detalhes do e-mail
        $details = [
            'email' => $request->email,
            'message' => $request->message,
        ];

        // Envia o e-mail para o destinatário
        Mail::to('alanmoreiraduart@gmail.com')->send(new ContactMail($details));

        // Redireciona de volta para a página anterior com uma mensagem de sucesso
        return back()->with('success', 'Sua mensagem foi enviada com sucesso!');
    }
}
