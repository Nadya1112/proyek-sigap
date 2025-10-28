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
        // ✅ Validasi form (tetap sama)
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha'  => ['required', 'numeric'],
        ]);

        // Validasi CAPTCHA (tetap sama)
        $expected = (int) (session('captcha.sum') ?? -1);
        if ((int) $request->input('captcha') !== $expected) {
            $this->makeCaptcha();
            return back()
                ->withErrors(['captcha' => 'Jawaban verifikasi tidak sesuai.'])
                ->withInput();
        }

        $login    = trim((string) $request->input('login'));
        $password = (string) $request->input('password');
        $remember = $request->boolean('remember');

        // Logika login ganda (email/kontak) (tetap sama)
        $attempts = [];
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $attempts[] = ['email' => $login, 'password' => $password];
        }
        $attempts[] = ['kontak' => $login, 'password' => $password];
        $digits = preg_replace('/\D+/', '', $login);
        if ($digits !== $login && $digits !== '') {
            $attempts[] = ['kontak' => $digits, 'password' => $password];
        }

        // Coba autentikasi
        foreach ($attempts as $creds) {
            if (Auth::attempt($creds, $remember)) {
                $request->session()->regenerate();
                session()->forget('captcha');

                $user = Auth::user(); // Dapatkan user yang login

                // ==========================================================
                // === PERUBAHAN UTAMA ADA DI SINI ===
                // ==========================================================
                // Kita ganti pengecekan $user->role === 'admin'
                // dengan fungsi helper isAdmin() dari Model User.
                if ($user->isAdmin()) { // Mengecek (Staff, JF PSU, Kabid, Kadis)
                    try {
                        return redirect()->route('filament.admin.pages.dashboard'); // Arahkan ke dashboard Filament
                    } catch (\Throwable $e) {
                        return redirect()->to('/admin'); // Fallback
                    }
                }
                
                // Jika BUKAN admin (role 'pengguna'), arahkan ke dashboard pengguna
                return redirect()->route('user.dashboard');
            }
        }

        // Gagal login (tetap sama)
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
