<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ProfileController extends Controller
{
    public function index(Request $request, $token = null)
    {
        $user = Auth::user();
        return view('profil.index', [
            'user' => $user,
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * FUNGSI DIPERBARUI: Hanya untuk memperbarui username.
     * Email dan kontak tidak lagi bisa diubah.
     */
    public function updateDetail(Request $request)
    {
        $user = Auth::user();

        // Validasi sekarang hanya untuk 'name'.
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        // Simpan hanya 'name'.
        $user->name = $validated['name'];
        $user->save();

        // Kembalikan dengan pesan sukses yang sesuai.
        return back()->with('status', 'detail-updated');
    }

    /**
     * METHOD BARU: Mengirim link reset password ke email pengguna yang sedang login.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = Auth::user();

        // Keamanan: Pastikan email yang di-submit adalah email user yang sedang login
        if ($request->email !== $user->email) {
            return back()->withErrors(['email' => 'Alamat email tidak cocok dengan akun Anda.']);
        }

        // Kirim link reset menggunakan sistem bawaan Laravel
        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', 'password-link-sent');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    /**
     * METHOD BARU: Menyimpan password baru setelah verifikasi token.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Gunakan sistem bawaan Laravel untuk mereset password
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            // Redirect kembali ke halaman profil utama dengan pesan sukses
            return redirect()->route('profil.index')->with('status', 'password-updated');
        }

        // Jika token tidak valid, kembali dengan error
        return back()->withInput(/*...*/)->withErrors(/*...*/)->with('active_tab', 'keamanan');
    }

    public function destroy(Request $request)
    {
        // 1. Validasi bahwa password yang dimasukkan tidak kosong
        $request->validate([
            'password_confirm' => ['required', 'string'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_confirm, $user->password)) {
            // Jika tidak cocok, kembalikan dengan pesan error DAN data tab aktif
            return back()
                ->withErrors(['password_confirm' => 'Password yang Anda masukkan salah.'])
                ->with('active_tab', 'bahaya'); // <--- Tambahan ini sangat penting
        }

        // 3. Jika password cocok, lanjutkan proses penghapusan
        Auth::logout(); // Logout pengguna

        $user->delete(); // Hapus data pengguna dari database

        // Hancurkan session dan buat token baru
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan ke halaman utama dengan pesan sukses
        return back()->withErrors(['password_confirm' => 'Password yang Anda masukkan salah.'])->with('active_tab', 'bahaya');
    }
}