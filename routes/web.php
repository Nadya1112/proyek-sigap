<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\FasumController;

/*
| Public pages
*/
Route::view('/', 'public.home')->name('home');
Route::view('/dashboard', 'public.dashboard')->name('dashboard');
Route::view('/fitur','public.fitur')->name('fitur');
Route::get('/informasi', [FasumController::class,'index'])->name('informasi');
Route::view('/sebaran-komplek','public.sebaran')->name('sebaran');
Route::view('/kontak','public.kontak')->name('kontak');
Route::view('/e-proposal-psu','public.eproposal')->name('eproposal');
Route::view('/pengaduan-masyarakat','public.pengaduan')->name('pengaduan');

/*
| Form handlers (POST)
*/
Route::post('/e-proposal-psu', [PublicFormController::class,'proposal'])->name('eproposal.store');
Route::post('/pengaduan-masyarakat', [PublicFormController::class,'pengaduan'])->name('pengaduan.store');
