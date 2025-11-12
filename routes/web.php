<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\EproposalController;
use App\Http\Controllers\FasumController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PengaduanController; // Pastikan ini di-import
use App\Http\Controllers\PetaSebaranController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\PublicFormController; // Tidak dipakai untuk pengaduan
use App\Http\Controllers\RegulasiController;
use App\Http\Controllers\UserDashboardController;

// --- PENAMBAHAN 1: IMPORT UNTUK SCRIPT ---
// Ini diperlukan agar skrip impor di bawah bisa berjalan
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
// --- BATAS PENAMBAHAN 1 ---

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
Route::view('/fitur', 'public.fitur')->name('fitur');
Route::get('/informasi-fasum', [FasumController::class, 'index'])->name('informasi-fasum');
Route::get('/regulasi', [RegulasiController::class, 'index'])->name('regulasi');
Route::get('/regulasi/unduh/{id}', [RegulasiController::class, 'download'])
    ->where('id', '[A-Za-z0-9_-]+')
    ->name('regulasi.download')
    ->middleware('throttle:60,1');
// Route ini sepertinya tidak dipakai lagi oleh EproposalController, tapi mungkin dipakai FasumController?
// Route::get('/kelurahan-by-kecamatan/{kecamatan}', [FasumController::class, 'kelurahanByKecamatan'])
//     ->name('kelurahan.byKecamatan');
Route::get('/sebaran-komplek', [PetaSebaranController::class, 'index'])->name('sebaran');
Route::view('/kontak', 'public.kontak')->name('kontak');
Route::get('/e-proposal-psu', [EproposalController::class, 'showForm'])->name('eproposal');
Route::get('/pengaduan-masyarakat', [PengaduanController::class, 'showPengaduanForm'])->name('pengaduan');
Route::view('/syarat-dan-ketentuan', 'public.syaratdanketentuan')->name('syaratdanketentuan');
Route::view('/kebijakan-privasi', 'public.kebijakanprivasi')->name('kebijakanprivasi');

/*
|--------------------------------------------------------------------------
| Form handlers (POST)
|--------------------------------------------------------------------------
*/
Route::post('/e-proposal-psu', [EproposalController::class, 'store'])->name('eproposal.store');
// PERBAIKAN: Arahkan ke PengaduanController
Route::post('/pengaduan-masyarakat', [PengaduanController::class, 'storePengaduan'])->name('pengaduan.store');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
// Auth Google
Route::get('/auth/google/redirect', [LoginController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

// Auth routes (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');
    // Rute verifikasi email (untuk registrasi)
    Route::get('/verify-email', [VerifyEmailController::class, 'showForm'])->name('verification.show');
    Route::post('/verify-email', [VerifyEmailController::class, 'verify'])->name('verification.verify');
    Route::post('/verify-email/resend', [VerifyEmailController::class, 'resend'])->name('verification.resend');
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'nocache'])->group(function () { // PERBAIKAN: Indentasi dimulai di sini
    // Dashboard
    // Route::view('/dashboard', 'public.dashboard')->name('dashboard'); // Mungkin tidak terpakai?
    Route::get('/dashboard-pengguna', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // Profil (Email tidak bisa diubah)
    Route::get('/profil/{token?}', [ProfileController::class, 'index'])->name('profil.index');
    Route::post('/profil/detail', [ProfileController::class, 'updateDetail'])->name('profil.update.detail');
    // PERBAIKAN: Hapus rute verifikasi email profil
    // Route::post('/profil/kirim-verifikasi', [ProfileController::class, 'sendVerification'])->name('profil.send_verification');
    // Route::post('/profil/verifikasi-email', [ProfileController::class, 'verifyEmail'])->name('profil.verify_email');
    Route::post('/profil/keamanan/kirim-link', [ProfileController::class, 'sendResetLink'])->name('profil.keamanan.kirim-link');
    Route::post('/profil/keamanan/reset', [ProfileController::class, 'resetPassword'])->name('profil.keamanan.reset');
    Route::delete('/profil/hapus', [ProfileController::class, 'destroy'])->name('profil.destroy');

    // E-Proposal Dropdown Data
    Route::get('/get-kelurahan/{kecamatanId}', [EproposalController::class, 'getKelurahan'])
         ->where('kecamatanId', '[0-9]+')
         ->name('get.kelurahan');
    Route::get('/get-kompleks-by-kelurahan/{kelurahanId}', [EproposalController::class, 'getKompleksByKelurahan'])
         ->where('kelurahanId', '[0-9]+')
         ->name('get.kompleks.by.kelurahan');

}); // Akhir grup middleware auth

/*
|--------------------------------------------------------------------------
| Download Template (Public)
|--------------------------------------------------------------------------
*/
Route::get('/unduh/template/proposal', [EproposalController::class, 'downloadTemplate'])
    ->name('template.proposal.download');

/*
|--------------------------------------------------------------------------
| Password Reset Routes (Guest)
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware('guest')->name('password.store');