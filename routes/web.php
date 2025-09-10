<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\FasumController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController; // <— tambahkan

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
Route::view('/informasi', 'public.home')->name('informasi'); // biar gak error kalau ada link lama ke /informasi

Route::get('/informasi-fasum', [FasumController::class,'index'])->name('informasi-fasum');

// Kompatibilitas link lama: /informasi → /informasi-fasum
Route::get('/informasi', fn () => redirect()->route('informasi-fasum'))->name('informasi');

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
| Auth routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

    // Register (halaman daftar akun)
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');

    // (opsional) Forgot password – aktifkan jika sudah siap controllernya
    // Route::get('/password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    // Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Dashboard (wajib login) – SATU SAJA
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'public.dashboard')->name('dashboard');
});
