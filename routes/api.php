<?php

use Illuminate\HttpRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PetaController; // Pastikan ini ada

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rute untuk mengambil data GeoJSON Kompleks (Titik)
Route::get('/kompleks', [PetaController::class, 'kompleks']);

// Rute untuk mengambil data GeoJSON Kecamatan (Poligon)
Route::get('/kecamatan', [PetaController::class, 'kecamatan']);

// Rute untuk mengambil data GeoJSON Kelurahan (Poligon)
Route::get('/kelurahan', [PetaController::class, 'kelurahan']); // <-- TAMBAHKAN INI


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});