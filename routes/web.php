<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\FasumController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Public pages (tanpa login)
|--------------------------------------------------------------------------
*/
Route::view('/home', 'public.home')->name('home');
Route::view('/fitur','public.fitur')->name('fitur');
// Route::view('/informasi', 'public.home')->name('informasi'); // biar gak error kalau ada link lama ke /informasi

Route::get('/informasi-fasum', [FasumController::class,'index'])->name('informasi-fasum');
// Endpoint dependent dropdown: ambil kelurahan berdasarkan kecamatan
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
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
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
    Route::view('/dashboard', 'public.dashboard')->name('dashboard'); // ganti ke view dashboard Anda
});
