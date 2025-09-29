<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmailCodeMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VerifyEmailController extends Controller
{
    public function showForm(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('register');
        }

        // Cek jika pengguna mencoba mengakses halaman ini tanpa melalui pendaftaran
        if (!$request->session()->has('registration_data')) {
             return redirect()->route('register')->withErrors(['email' => 'Sesi pendaftaran tidak ditemukan. Silakan daftar ulang.']);
        }

        return view('auth.verify-email', compact('email'));
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code'  => ['required', 'digits:4'],
        ]);

        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData || $registrationData['email'] !== $data['email']) {
            return back()->withErrors(['email' => 'Sesi pendaftaran tidak valid atau email tidak cocok.'])->withInput();
        }

        // =====================================================================
        // === PENGECEKAN WAKTU KEDALUWARSA YANG SUDAH AKTIF ===
        // =====================================================================
        if (Carbon::parse($registrationData['verification_expires_at'])->isPast()) {
            return back()->withErrors(['code' => 'Kode verifikasi sudah kedaluwarsa. Silakan minta kode baru.'])->withInput();
        }

        $inputCode = preg_replace('/\D/', '', $data['code']);
        
        // Diubah agar menggunakan Hash::check jika Anda menerapkan hashing
        if (!\Illuminate\Support\Facades\Hash::check($inputCode, $registrationData['verification_code'])) {
            return back()->withErrors(['code' => 'Kode verifikasi yang Anda masukkan salah.'])->withInput();
        }

        $user = User::create([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'kontak' => $registrationData['kontak'],
            'password' => $registrationData['password'],
            'email_verified_at' => now(),
            'role' => 'pengguna'
        ]);

        $request->session()->forget('registration_data');

        return redirect()->route('login')->with('status', 'Verifikasi berhasil! Silakan masuk dengan akun baru Anda.');
    }

    public function resend(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // =====================================================================
        // === LOGIKA RESEND DIPERBAIKI AGAR MENGGUNAKAN SESSION ===
        // =====================================================================
        $registrationData = $request->session()->get('registration_data');

        // Pastikan ada sesi dan emailnya cocok
        if (!$registrationData || $registrationData['email'] !== $request->email) {
            return redirect()->route('register')->withErrors(['email' => 'Gagal mengirim ulang kode. Sesi tidak ditemukan.']);
        }

        // Buat kode dan waktu kedaluwarsa yang baru (2 MENIT)
        $plainTextCode = (string) random_int(1000, 9999);
        $registrationData['verification_code'] = \Illuminate\Support\Facades\Hash::make($plainTextCode);
        $registrationData['verification_expires_at'] = now()->addMinutes(2);

        // Perbarui data di session
        $request->session()->put('registration_data', $registrationData);

        // Kirim ulang email dengan kode baru
        $tempUser = new User($registrationData);
        $tempUser->verification_code = $plainTextCode; // Kirim kode asli ke email
        
        Mail::to($registrationData['email'])->send(new VerifyEmailCodeMail($tempUser));

        return redirect()
            ->route('verification.show', ['email' => $registrationData['email']])
            ->with('status', 'Kode verifikasi baru telah dikirim ulang ke email Anda.');
    }
}