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
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code'  => ['required', 'digits:4'],
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        // Jika sudah verified, arahkan ke login
        if ($user->email_verified_at) {
            return redirect()->route('login')->with('status', 'Email sudah terverifikasi. Silakan masuk.');
        }

        // Bersihkan kemungkinan spasi/garis/karakter non-digit
        $inputCode = preg_replace('/\D/', '', $data['code']);

        if (!$user->verification_code || $user->verification_code !== $inputCode) {
            return back()->withErrors(['code' => 'Kode verifikasi salah.'])->withInput();
        }

        if ($user->verification_expires_at && Carbon::parse($user->verification_expires_at)->isPast()) {
            return back()->withErrors(['code' => 'Kode verifikasi sudah kedaluwarsa.'])->withInput();
        }

        $user->email_verified_at        = now();
        $user->verification_code        = null;
        $user->verification_expires_at  = null;
        $user->save();

        return redirect()->route('login')->with('status', 'Email berhasil diverifikasi. Silakan masuk.');
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
