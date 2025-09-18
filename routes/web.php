<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\FasumController;
use App\Http\Controllers\RegulasiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;

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
Route::view('/e-proposal-psu','public.eproposal')->name('eproposal');
Route::view('/pengaduan-masyarakat','public.pengaduan')->name('pengaduan');

/*
|--------------------------------------------------------------------------
| Form handlers (POST)
|--------------------------------------------------------------------------
*/
Route::post('/e-proposal-psu', [PublicFormController::class,'proposal'])->name('eproposal.store');
Route::post('/pengaduan-masyarakat', [PublicFormController::class,'pengaduan'])->name('pengaduan.store');

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
    Route::view('/dashboard', 'public.dashboard')->name('dashboard');
});
