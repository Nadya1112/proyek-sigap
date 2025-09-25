<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmailCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

   public function store(Request $request)
    {
        // 1. Validasi input dari form pendaftaran
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'kontak'   => ['required', 'string', 'max:20', 'unique:users,kontak'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'syaratdanketentuan'    => ['accepted']
        ], [
            // (Anda bisa tambahkan pesan custom di sini jika perlu)
            'kontak.required'   => 'Nomor HP wajib diisi.',
            'syaratdanketentuan.accepted'    => 'Anda harus menyetujui Syarat & Ketentuan.',
        ]);

        // 2. Siapkan semua data yang akan disimpan nanti (termasuk kode verifikasi)
        $registrationData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'kontak' => $validated['kontak'],
            'password' => Hash::make($validated['password']), // Langsung hash passwordnya
            'verification_code' => (string) random_int(1000, 9999),
            'verification_expires_at' => now()->addMinutes(10),
        ];

        // 3. Simpan semua data di atas ke dalam SESSION, BUKAN DATABASE
        $request->session()->put('registration_data', $registrationData);

        // 4. Kirim email verifikasi menggunakan data dari session
        try {
            // Buat objek user sementara HANYA untuk dikirim ke email
            $tempUser = new User($registrationData);
            
            Mail::to($registrationData['email'])->send(new VerifyEmailCodeMail($tempUser));

        } catch (\Exception $e) {
            // Jika email gagal terkirim (misal: config .env salah), hentikan proses
            // dan beri pesan error yang jelas.
            return back()->withInput()->withErrors(['email' => 'Gagal mengirim email verifikasi. Silakan coba lagi nanti.']);
        }
        
        // 5. Arahkan pengguna ke halaman verifikasi seperti biasa
        return redirect()->route('verification.show', ['email' => $validated['email']])
            ->with('status', 'Pendaftaran hampir selesai! Kode verifikasi telah dikirim ke email Anda.');
    }
}