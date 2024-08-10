<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Adm;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Retorna a view do formulário de login
    }

    /**
     * Processa o login do administrador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Recebe as credenciais (usuário e senha) do request
        $credentials = $request->only('username', 'password');

        // Tenta autenticar o administrador com as credenciais fornecidas
        if (Auth::guard('adm')->attempt($credentials)) {
            // Se a autenticação for bem-sucedida, redireciona para a rota dos cards
            return redirect()->intended('/cards');
        }

        // Se a autenticação falhar, retorna ao login com uma mensagem de erro
        return redirect('login')->withErrors(['login' => 'Credenciais inválidas']);
    }

    /**
     * Faz o logout do administrador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Faz o logout do administrador
        Auth::guard('adm')->logout();

        // Invalida a sessão atual e gera um novo token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redireciona para a página de login
        return redirect('/login');
    }
}
