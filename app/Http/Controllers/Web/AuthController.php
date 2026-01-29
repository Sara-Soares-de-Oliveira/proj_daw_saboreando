<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InternalApi;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showPassword()
    {
        return view('auth.password');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $response = InternalApi::request('POST', '/api/auth/login', $validated);

        if (!$response['ok']) {
            return back()->withErrors(['email' => 'Credenciais inválidas.'])->withInput();
        }

        $data = $response['json'] ?? [];
        $request->session()->put('api_token', $data['token'] ?? null);
        $request->session()->put('user', $data['user'] ?? null);

        $role = $data['user']['role'] ?? 'explorador';

        return $role === 'moderador'
            ? redirect('/moderador')
            : redirect('/home');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $response = InternalApi::request('POST', '/api/auth/register', $validated);

        if (!$response['ok']) {
            return back()->withErrors(['email' => 'Não foi possível registar.'])->withInput();
        }

        $data = $response['json'] ?? [];
        $request->session()->put('api_token', $data['token'] ?? null);
        $request->session()->put('user', $data['user'] ?? null);

        return redirect('/home');
    }

    public function logout(Request $request)
    {
        $token = $request->session()->get('api_token');

        if ($token) {
            InternalApi::request('POST', '/api/auth/logout', [], $token);
        }

        $request->session()->forget(['api_token', 'user']);

        return redirect('/entrar');
    }
}
