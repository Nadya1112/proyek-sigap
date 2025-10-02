<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\FasumController;
use App\Http\Controllers\RegulasiController;         // <- pastikan controller ini ada (rename dari InformasiController)
use App\Http\Controllers\UserDashboardController;     // <- controller baru (di langkah C)
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\EproposalController;

/*
|--------------------------------------------------------------------------
| Redirect root
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/home');

/*
|--------------------------------------------------------------------------
| Public pages (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::view('/fitur','public.fitur')->name('fitur');

// FASUM lama (kalau masih dipakai)
Route::get('/informasi-fasum', [FasumController::class,'index'])->name('informasi-fasum');

// INFORMASI baru + unduh (pakai fileId langsung)
// routes/web.php
Route::redirect('/informasi', '/regulasi', 301);
Route::get('/informasi/unduh/{id}', fn($id) => redirect()->away(route('regulasi.download', $id), 301));

// --- rute baru
Route::get('/regulasi', [RegulasiController::class, 'index'])->name('regulasi');

Route::get('/regulasi/unduh/{id}', [RegulasiController::class, 'download'])
    ->where('id', '[A-Za-z0-9_-]+')
    ->name('regulasi.download')
    ->middleware('throttle:60,1');

// opsional: redirect lama → baru (SEO & link lama tetap hidup)
Route::redirect('/informasi', '/regulasi', 301);
Route::get('/informasi/unduh/{id}', fn($id)=>redirect()->to(route('regulasi.download',$id),301));

// dependent dropdown
Route::get('/kelurahan-by-kecamatan/{kecamatan}', [FasumController::class, 'kelurahanByKecamatan'])
    ->name('kelurahan.byKecamatan');

Route::view('/sebaran-komplek','public.sebaran')->name('sebaran');
Route::view('/kontak','public.kontak')->name('kontak');
Route::get('/e-proposal-psu', [EproposalController::class, 'showForm'])->name('eproposal');
// Route::view('/e-proposal-psu','public.eproposal')->name('eproposal');
Route::get('/pengaduan-masyarakat', [PengaduanController::class, 'showPengaduanForm'])->name('pengaduan');



// Rute untuk halaman Syarat & Ketentuan dan Kebijakan Privasi
Route::view('/syarat-dan-ketentuan', 'public.syaratdanketentuan')->name('syaratdanketentuan');
Route::view('/kebijakan-privasi', 'public.kebijakanprivasi')->name('kebijakanprivasi');


/*
|--------------------------------------------------------------------------
| Form handlers (POST)
|--------------------------------------------------------------------------
*/
Route::post('/e-proposal-psu', [EproposalController::class, 'store'])->name('eproposal.store');
// Route::post('/e-proposal-psu', [PublicFormController::class,'proposal'])->name('eproposal.store');
Route::post('/pengaduan-masyarakat', [PublicFormController::class,'pengaduan'])->name('pengaduan.store');

Route::get('/auth/google/redirect', [\App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Auth routes (khusus TAMU)
|--------------------------------------------------------------------------
| Penting: gunakan 'guest' agar user yang SUDAH login diarahkan
| ke home/dashboard, dan TIDAK terjadi redirect loop di /login.
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');

    Route::get('/verify-email', [VerifyEmailController::class, 'showForm'])->name('verification.show');
    Route::post('/verify-email', [VerifyEmailController::class, 'verify'])->name('verification.verify');
    Route::post('/verify-email/resend', [VerifyEmailController::class, 'resend'])->name('verification.resend');
});

// logout (hanya untuk yang sudah login)
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Dashboard (WAJIB LOGIN) + NoCache agar tombol Back tidak menampilkan cache
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','nocache'])->group(function () {
    // (punya kamu) jika ada dashboard umum
    Route::view('/dashboard', 'public.dashboard')->name('dashboard');

    // === DASHBOARD PENGGUNA ===
    Route::get('/dashboard-pengguna', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');
    
    // *** HALAMAN PROFIL AKUN *** //
    // Menampilkan halaman profil utama, sekarang bisa menerima token opsional
    Route::get('/profil/{token?}', [ProfileController::class, 'index'])->name('profil.index');
    
    // Memperbarui detail (Nama, Email, Kontak) - Tetap sama
    Route::post('/profil/detail', [ProfileController::class, 'updateDetail'])->name('profil.update.detail');
    
    // Rute BARU untuk mengirim link reset dari halaman profil
    Route::post('/profil/keamanan/kirim-link', [ProfileController::class, 'sendResetLink'])->name('profil.keamanan.kirim-link');
    
    // Rute BARU untuk menyimpan password baru setelah link di-klik
    Route::post('/profil/keamanan/reset', [ProfileController::class, 'resetPassword'])->name('profil.keamanan.reset');

    // RUTE BARU UNTUK MENGHAPUS AKUN
    Route::delete('/profil/hapus', [ProfileController::class, 'destroy'])->name('profil.destroy');

    // RUTE UNTUK MENDETEKSI KEMIRIPAN NAMA KOMPLEK PERUMAHAN
    Route::get('/search-kompleks', [EproposalController::class, 'searchKompleks'])->name('kompleks.search');

    });

// --- RUTE UNTUK LUPA KATA SANDI ---

// Menampilkan form untuk memasukkan email
Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

// Mengirim link reset ke email
Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// Menampilkan form untuk membuat password baru (setelah klik link di email)
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

// Menyimpan password baru
Route::post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');