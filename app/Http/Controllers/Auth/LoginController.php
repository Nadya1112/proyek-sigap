<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // resources/views/auth/login.blade.php
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'login'    => ['required','string'], // email / username / phone (sesuaikan)
            'password' => ['required','string'],
        ]);

        // Secara default pakai email. Jika Anda punya kolom username/phone, ubah logika ini.
        $field = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'email';
        // contoh jika punya kolom 'username':
        // $field = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $validated['login'], 'password' => $validated['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors(['login' => 'Kredensial tidak sesuai.'])
            ->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
