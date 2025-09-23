<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /** Buat soal captcha & simpan ke session */
    private function makeCaptcha(): array
    {
        $a = random_int(1, 9);
        $b = random_int(1, 9);

        session([
            'captcha' => [
                'a'   => $a,
                'b'   => $b,
                'sum' => $a + $b,
            ],
        ]);

        return [$a, $b];
    }

    /** GET /login (guest) */
    public function showLoginForm()
    {
        // siapkan captcha di setiap kunjungan halaman login
        [$a, $b] = $this->makeCaptcha();

        // kirim $a dan $b ke view (login.blade.php kamu memang memakainya)
        return view('auth.login', compact('a', 'b'));
    }

    /** POST /login */
    public function authenticate(Request $request)
    {
        // ✅ sesuai form kamu: 'login' (email/HP), 'password', 'captcha'
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha'  => ['required', 'numeric'],
        ]);

        // Validasi CAPTCHA
        $expected = (int) (session('captcha.sum') ?? -1);
        if ((int) $request->input('captcha') !== $expected) {
            // regenerate soal baru supaya tidak bisa brute force
            $this->makeCaptcha();

            return back()
                ->withErrors(['captcha' => 'Jawaban verifikasi tidak sesuai.'])
                ->withInput();
        }

        $login    = trim((string) $request->input('login'));
        $password = (string) $request->input('password');
        $remember = $request->boolean('remember');

        // Siapkan beberapa kemungkinan kredensial:
        // 1) jika 'login' adalah email valid -> cek dengan kolom email
        // 2) cek dengan kolom kontak (apa adanya)
        // 3) cek dengan kolom kontak (hanya digit, kalau user memasukkan format dengan spasi/tanda)
        $attempts = [];

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $attempts[] = ['email' => $login, 'password' => $password];
        }

        $attempts[] = ['kontak' => $login, 'password' => $password];

        $digits = preg_replace('/\D+/', '', $login);
        if ($digits !== $login && $digits !== '') {
            $attempts[] = ['kontak' => $digits, 'password' => $password];
        }

        // Coba autentikasi dengan setiap kemungkinan di atas
        foreach ($attempts as $creds) {
            if (Auth::attempt($creds, $remember)) {
                $request->session()->regenerate();
                session()->forget('captcha'); // bersihkan captcha agar tidak mengganggu alur selanjutnya

                $user = Auth::user();

                // Role-based redirect (sesuai permintaanmu)
                if ($user->role === 'admin') {
                    try {
                        return redirect()->route('filament.admin.pages.dashboard'); // Filament v3
                    } catch (\Throwable $e) {
                        return redirect()->to('/admin'); // fallback panel default
                    }
                }
                // pengguna -> landing page (home.blade)
                return redirect()->route('home');
            }
        }

        // Gagal login -> regen captcha + pesan
        $this->makeCaptcha();

        return back()
            ->withErrors(['login' => 'Kredensial tidak cocok.'])
            ->withInput();
    }

    /** POST /logout */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
