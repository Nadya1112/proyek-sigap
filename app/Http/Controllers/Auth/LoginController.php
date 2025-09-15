<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // Buat soal captcha setiap kali halaman login dibuka
        [$a, $b] = $this->generateCaptcha($request);

        // Tampilkan angka ke view
        return view('auth.login', compact('a', 'b'));
    }

    public function authenticate(Request $request)
    {
        // Validasi input (tetap seperti punyamu + tambahkan captcha)
        $validated = $request->validate([
            'login'    => ['required','string'], // email / username / phone
            'password' => ['required','string'],
            'captcha'  => ['required','numeric'],
        ], [
            'captcha.required' => 'Jawaban verifikasi wajib diisi.',
            'captcha.numeric'  => 'Jawaban verifikasi harus berupa angka.',
        ]);

        // Cek captcha lebih dulu (TANPA mengubah algoritma loginmu)
        $expected = (int) $request->session()->get('login_captcha_sum', -1);
        if ((int) $validated['captcha'] !== $expected) {
            // Soal baru untuk percobaan berikutnya
            $this->generateCaptcha($request);

            return back()
                ->withErrors(['captcha' => 'Jawaban verifikasi salah. Silakan coba lagi.'])
                ->onlyInput('login'); // login tetap diisi, password dikosongkan
        }

        // Algoritma loginmu tetap sama
        $field = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'email';
        // contoh bila nanti pakai username:
        // $field = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $validated['login'], 'password' => $validated['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Hapus captcha dari session setelah sukses
            $request->session()->forget(['login_captcha_sum', 'login_captcha_a', 'login_captcha_b']);

            return redirect()->intended(route('home'));
        }

        // Kredensial salah -> buat soal baru lagi
        $this->generateCaptcha($request);

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

    /**
     * Generate captcha penjumlahan dan simpan hasilnya di session.
     * @return array{int,int} [$a,$b]
     */
    private function generateCaptcha(Request $request): array
    {
        $a = random_int(10, 49);
        $b = random_int(1, 9);

        $request->session()->put('login_captcha_sum', $a + $b);
        $request->session()->put('login_captcha_a', $a);
        $request->session()->put('login_captcha_b', $b);

        return [$a, $b];
    }
}
