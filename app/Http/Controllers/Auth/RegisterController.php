<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Schema;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)], // min 8 sesuai permintaan
            'terms'    => ['accepted'],
        ];

        // Wajibkan jika kolom tersedia di tabel users
        if (Schema::hasColumn('users', 'username')) {
            $rules['username'] = ['required', 'string', 'max:50', 'unique:users,username'];
        }
        if (Schema::hasColumn('users', 'phone')) {
            $rules['phone'] = ['required', 'string', 'max:20', 'unique:users,phone'];
        }

        $messages = [
            'name.required'          => 'Nama wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah terdaftar.',
            'username.required'      => 'Username wajib diisi.',
            'username.unique'        => 'Username sudah digunakan.',
            'phone.required'         => 'Nomor HP wajib diisi.',
            'phone.unique'           => 'Nomor HP sudah digunakan.',
            'password.required'      => 'Kata sandi wajib diisi.',
            'password.confirmed'     => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'           => 'Kata sandi minimal :min karakter.',
            'terms.accepted'         => 'Anda harus menyetujui Syarat & Ketentuan.',
        ];

        $data = $request->validate($rules, $messages);

        $user = new User();
        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (Schema::hasColumn('users', 'username')) {
            $user->username = $data['username'];
        }
        if (Schema::hasColumn('users', 'phone')) {
            $user->phone = $data['phone'];
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('status', 'Akun berhasil dibuat. Selamat datang!');
    }
}
