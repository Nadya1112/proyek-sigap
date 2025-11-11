<?php

use Illuminate\Http\Request; // <-- PERBAIKAN: Seharusnya 'Http\Request'
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
Route::get('/kelurahan', [PetaController::class, 'kelurahan']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});