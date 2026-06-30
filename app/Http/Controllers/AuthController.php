<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller{
    // Mostrar formulário de login
    public function login(){
        return view('autenticacao.login');
    }

    // Processar login com username ou email
    public function authenticate(Request $request){
        $login = $request->input('username');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Verifica se o usuário existe
        $user = User::where($fieldType, $login)->first();

        // Verifica se o usuário existe e se o status de aprovação é 0 ou se o role é 'pending'
        if (!$user || $user->role === 'pending') {
            return back()->withErrors([
                'username' => 'Usuário ainda não aprovado pelo administrador.',
            ])->withInput();
        }

        // Tenta fazer o login
        if (Auth::attempt([$fieldType => $login, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('convenio.index'));
        }

        return back()->withErrors([
            'username' => 'Usuário ou senha incorretos.'
        ])->withInput();
    }

    // Logout
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Desabilitar cache da página
        return redirect()->route('login')->withHeaders([
            'Cache-Control' => 'no-store',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
    // Mostrar formulário de registro
    public function showRegisterForm(){
        return view('autenticacao.register');
    }

    // Processar registro
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',  // Tornando email opcional
            'username' => 'required|string|unique:users|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'pending',
            'approved' => false,
        ]);


        return redirect()->route('login')->with('success', 'Cadastro enviado para aprovação do administrador master.');
    }
    // Exibir requisições de administradores pendentes (apenas para admin_master)
    public function requisicoesPendentes(Request $request){
        if (!auth()->user()->isAdminMaster()) {
            abort(403);
        }

        $usuarios = User::where('role', 'pending')->get();

        /*if ($request->ajax()) {
        return view('convenirequisicoes', compact('usuarios'));
    }*/
        //dd($usuarios);
        return view('convenios.requisicoes', compact('usuarios'));
    }

    // Aprovar novo administrador
    public function approveAdmin($id){
        if (!auth()->user()->isAdminMaster()) {
            abort(403);
        }

        $usuario = User::findOrFail($id);
        $usuario->role = 'admin';
        $usuario->approved = 1;
        $usuario->save();

        return redirect()->back()->with('success', 'Usuário aprovado com sucesso!');
    }
}
