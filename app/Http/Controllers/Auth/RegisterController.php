<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmailCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        // Validasi
        $rules = [
            'name'     => ['required','string','max:255'],
            'email'    => ['required','string','lowercase','email','max:255','unique:users,email'],
            'password' => ['required','confirmed', Password::min(8)],
        ];

        if (Schema::hasColumn('users', 'username')) {
            $rules['username'] = ['required','string','max:50','unique:users,username'];
        }
        if (Schema::hasColumn('users', 'phone')) {
            $rules['phone'] = ['required','string','max:20','unique:users,phone'];
        }

        $data = $request->validate($rules, [
            'name.required'         => 'Nama wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar.',
            'username.required'     => 'Username wajib diisi.',
            'phone.required'        => 'Nomor HP wajib diisi.',
            'password.required'     => 'Kata sandi wajib diisi.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Simpan user
        $user = new User();
        $user->name  = $data['name'];
        $user->email = $data['email'];
        if (isset($data['username'])) $user->username = $data['username'];
        if (isset($data['phone']))    $user->phone    = $data['phone'];
        $user->password = Hash::make($data['password']);

        // Generate kode verifikasi (4 digit) + masa berlaku
        $code = (string) random_int(1000, 9999);
        $user->verification_code       = $code;
        $user->verification_expires_at = now()->addMinutes(10);

        $user->save();

        // Kirim email
        Mail::to($user->email)->send(new \App\Mail\VerifyEmailCodeMail($user));
        // Mail::to($user->email)->send(new VerifyEmailCodeMail($user->name, $code));

        // Arahkan ke halaman verifikasi (bawa email agar auto-terisi)
        return redirect()->route('verification.show', ['email' => $user->email])
            ->with('status', 'Kode verifikasi dikirim ke email Anda.');
    }
}
