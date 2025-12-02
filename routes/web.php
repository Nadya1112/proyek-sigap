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
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PetaSebaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegulasiController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
// TAMBAHAN MODEL UNTUK PERBAIKAN DB
use App\Models\Komplek; 

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
Route::post('/pengaduan-masyarakat', [PengaduanController::class, 'storePengaduan'])->name('pengaduan.store');

/*
|--------------------------------------------------------------------------
| AJAX Data Endpoints (Public)
|--------------------------------------------------------------------------
*/
Route::get('/get-kelurahan/{kecamatanId}', [EproposalController::class, 'getKelurahan'])
    ->where('kecamatanId', '[0-9]+')
    ->name('get.kelurahan');
Route::get('/get-kompleks-by-kelurahan/{kelurahanId}', [EproposalController::class, 'getKompleksByKelurahan'])
    ->where('kelurahanId', '[0-9]+')
    ->name('get.kompleks.by.kelurahan');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/auth/google/redirect', [LoginController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');
    Route::get('/verify-email', [VerifyEmailController::class, 'showForm'])->name('verification.show');
    Route::post('/verify-email', [VerifyEmailController::class, 'verify'])->name('verification.verify');
    Route::post('/verify-email/resend', [VerifyEmailController::class, 'resend'])->name('verification.resend');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'nocache'])->group(function () { 
    Route::get('/dashboard-pengguna', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/profil/{token?}', [ProfileController::class, 'index'])->name('profil.index');
    Route::post('/profil/detail', [ProfileController::class, 'updateDetail'])->name('profil.update.detail');
    Route::post('/profil/keamanan/kirim-link', [ProfileController::class, 'sendResetLink'])->name('profil.keamanan.kirim-link');
    Route::post('/profil/keamanan/reset', [ProfileController::class, 'resetPassword'])->name('profil.keamanan.reset');
    Route::delete('/profil/hapus', [ProfileController::class, 'destroy'])->name('profil.destroy');
});

Route::get('/unduh/template/proposal', [EproposalController::class, 'downloadTemplate'])
    ->name('template.proposal.download');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware('guest')->name('password.store');

// ==========================================================================
// ROUTE KHUSUS PERBAIKAN DATABASE (JALANKAN SEKALI LALU HAPUS)
// ==========================================================================
Route::get('/perbaiki-database-koordinat', function () {
    $data = Komplek::all();
    $count = 0;
    foreach ($data as $item) {
        $lat = (float) $item->latitude;
        $lng = (float) $item->longitude;
        if ($lat == 0 || $lng == 0) continue;
        $latAwal = $lat;
        
        // Normalisasi Latitude (-90 s/d 90)
        while (abs($lat) > 90) { $lat /= 10; }
        // Normalisasi Longitude (-180 s/d 180)
        while (abs($lng) > 180) { $lng /= 10; }

        if ($lat != $latAwal) { 
            $item->latitude = $lat;
            $item->longitude = $lng;
            $item->save();
            $count++;
        }
    }
    return "SUKSES! $count data kompleks berhasil diperbaiki. Sekarang buka halaman peta.";
});