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

        // Jika query kosong, arahkan balik saja ke daftar / login agar tidak 404
        if (!$email) {
            return redirect()->route('register')->with('error', 'Email tidak ditemukan. Silakan daftar kembali.');
        }

        // Opsional: jika email sudah terverifikasi, langsung ke login
        if ($u = User::where('email', $email)->first()) {
            if ($u->email_verified_at) {
                return redirect()->route('login')->with('status', 'Email sudah terverifikasi. Silakan masuk.');
            }
        }

        return view('auth.verify-email', compact('email'));
    }

    public function verify(Request $request)
    {
        // 1. Validasi input dari form verifikasi (email dan kode)
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code'  => ['required', 'digits:4'],
        ]);

        // 2. Ambil data pendaftaran yang sebelumnya kita simpan di session
        $registrationData = $request->session()->get('registration_data');

        // 3. Lakukan serangkaian pengecekan keamanan
        if (!$registrationData || $registrationData['email'] !== $data['email']) {
            return back()->withErrors(['email' => 'Sesi pendaftaran tidak valid atau email tidak cocok. Silakan daftar ulang.'])->withInput();
        }

        if (Carbon::parse($registrationData['verification_expires_at'])->isPast()) {
            return back()->withErrors(['code' => 'Kode verifikasi sudah kedaluwarsa. Silakan kirim ulang kode.'])->withInput();
        }

        $inputCode = preg_replace('/\D/', '', $data['code']);
        if ($registrationData['verification_code'] !== $inputCode) {
            return back()->withErrors(['code' => 'Kode verifikasi yang Anda masukkan salah.'])->withInput();
        }

        // =====================================================================
        // === PROSES PEMBUATAN AKUN BARU SETELAH SEMUA PENGECEKAN BERHASIL ===
        // =====================================================================
        
        // 4. Buat user baru di database menggunakan data dari session
        $user = User::create([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'kontak' => $registrationData['kontak'],
            'password' => $registrationData['password'], // Password sudah di-hash
            'email_verified_at' => now(), // Langsung set terverifikasi
            'role' => 'pengguna' // Atur role default untuk pengguna baru
        ]);

        // 5. Hapus data dari session agar tidak bisa dipakai lagi
        $request->session()->forget('registration_data');

        // 6. Arahkan ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('status', 'Verifikasi berhasil! Silakan masuk dengan akun baru Anda.');
    }

    public function resend(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            // Kembalikan ke form verify dengan email yang sama agar UX tidak bingung
            return redirect()
                ->route('verification.show', ['email' => $request->email])
                ->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if ($user->email_verified_at) {
            return redirect()->route('login')->with('status', 'Email sudah terverifikasi. Silakan masuk.');
        }

        $user->verification_code       = (string) random_int(1000, 9999);
        $user->verification_expires_at = now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new VerifyEmailCodeMail($user));

        // Redirect eksplisit ke halaman verify (bukan sekadar back) supaya konsisten
        return redirect()
            ->route('verification.show', ['email' => $user->email])
            ->with('status', 'Kode verifikasi baru telah dikirim.');
    }
}
