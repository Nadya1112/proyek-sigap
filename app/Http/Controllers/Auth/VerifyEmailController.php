<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailCodeMail;

class VerifyEmailController extends Controller
{
    public function showForm(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('register');
        }

        if (!$request->session()->has('registration_data')) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi pendaftaran tidak ditemukan. Silakan daftar ulang.']);
        }

        return view('auth.verify-email', compact('email'));
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            // DIUBAH: nama field validasi disamakan dengan controller sebelumnya
            'verification_code' => ['required', 'numeric', 'digits:4'],
        ]);

        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData || $registrationData['email'] !== $data['email']) {
            return back()->withErrors(['email' => 'Sesi pendaftaran tidak valid atau email tidak cocok.'])->withInput();
        }

        if (Carbon::parse($registrationData['verification_expires_at'])->isPast()) {
            return back()->withErrors(['verification_code' => 'Kode verifikasi sudah kedaluwarsa.'])->withInput();
        }

        // DIUBAH: Gunakan perbandingan biasa, bukan Hash::check
        if ($registrationData['verification_code'] !== $data['verification_code']) {
            return back()->withErrors(['verification_code' => 'Kode verifikasi yang Anda masukkan salah.'])->withInput();
        }

        // Hapus data verifikasi dari array sebelum membuat user
        unset($registrationData['verification_code'], $registrationData['verification_expires_at']);
        
        // Tambahkan waktu verifikasi email
        $registrationData['email_verified_at'] = now();

        $user = User::create($registrationData);

        $request->session()->forget('registration_data');
        
        // Login-kan pengguna secara otomatis
        Auth::login($user);

        // Arahkan ke dashboard setelah verifikasi berhasil
        return redirect()->route('user.dashboard')->with('status', 'Verifikasi berhasil! Selamat datang.');
    }

    public function resend(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData || $registrationData['email'] !== $request->email) {
            return redirect()->route('register')->withErrors(['email' => 'Gagal mengirim ulang kode. Sesi tidak ditemukan.']);
        }

        // DIUBAH: Buat kode teks biasa (plain text), jangan di-hash
        $newCode = (string) random_int(1000, 9999);
        $registrationData['verification_code'] = $newCode;
        $registrationData['verification_expires_at'] = now()->addMinutes(10);

        $request->session()->put('registration_data', $registrationData);

        // Kirim kode baru ke email
        $tempUser = new User($registrationData);
        Mail::to($registrationData['email'])->send(new VerifyEmailCodeMail($tempUser));

        return redirect()
            ->route('verification.show', ['email' => $registrationData['email']])
            ->with('status', 'Kode verifikasi baru telah dikirim ulang ke email Anda.');
    }
}