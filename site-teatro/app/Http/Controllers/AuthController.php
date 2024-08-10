<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Adm;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->only('username', 'password');

    if (Auth::guard('adm')->attempt($credentials)) {
        // Autenticado com sucesso
        return redirect()->intended('/cards'); // ou a rota desejada
    }

    // Falha na autenticação
    return redirect('login')->withErrors(['login' => 'Credenciais inválidas']);
}

    public function logout(Request $request)
    {
        // Remove a variável de sessão que indica que o usuário está autenticado
        $request->session()->forget('authenticated');

        // Redireciona para a página de login
        return redirect()->route('login');
    }
}
