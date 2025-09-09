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
    /**
     * Tampilkan halaman daftar.
     */
    public function showForm()
    {
        // resources/views/auth/register.blade.php (sudah kamu buat)
        return view('auth.register');
    }

    /**
     * Proses pendaftaran user baru.
     */
    public function store(Request $request)
    {
        // Aturan dasar yang selalu ada
        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'terms'    => ['accepted'],
        ];

        // Jika tabel users memiliki kolom opsional ini, validasi juga
        if (Schema::hasColumn('users', 'username')) {
            $rules['username'] = ['nullable', 'string', 'max:50', 'unique:users,username'];
        }
        if (Schema::hasColumn('users', 'phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20', 'unique:users,phone'];
        }

        $messages = [
            'name.required'        => 'Nama wajib diisi.',
            'email.required'       => 'Email wajib diisi.',
            'email.email'          => 'Format email tidak valid.',
            'email.unique'         => 'Email sudah terdaftar.',
            'username.unique'      => 'Username sudah digunakan.',
            'phone.unique'         => 'Nomor HP sudah digunakan.',
            'password.required'    => 'Kata sandi wajib diisi.',
            'password.confirmed'   => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'         => 'Kata sandi minimal :min karakter.',
            'terms.accepted'       => 'Anda harus menyetujui Syarat & Ketentuan.',
        ];

        $data = $request->validate($rules, $messages);

        // Buat user baru
        $user = new User();
        $user->name  = $data['name'];
        $user->email = $data['email'];

        // Set opsional hanya jika kolom ada
        if (Schema::hasColumn('users', 'username') && isset($data['username'])) {
            $user->username = $data['username'];
        }
        if (Schema::hasColumn('users', 'phone') && isset($data['phone'])) {
            $user->phone = $data['phone'];
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        // Auto login setelah daftar
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('status', 'Akun berhasil dibuat. Selamat datang!');
    }
}
