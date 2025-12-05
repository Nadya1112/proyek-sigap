<?php

use Illuminate\Http\Request; // <-- PERBAIKAN: Seharusnya 'Http\Request'
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PetaController; // Pastikan ini ada
use App\Models\Komplek;
use App\Models\Kelurahan;
use App\Models\Kecamatan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// API untuk Data Peta
Route::get('/kompleks', function () {
    $kompleks = Komplek::all();
    
    // Format GeoJSON
    $features = $kompleks->map(function($k) {
        return [
            'type' => 'Feature',
            'properties' => $k, // Kirim semua data komplek
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [(float)$k->longitude, (float)$k->latitude] // Ingat: [Lng, Lat]
            ]
        ];
    });

    return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
});

// Rute untuk mengambil data GeoJSON Kompleks (Titik)
Route::get('/kompleks', [PetaController::class, 'kompleks']);

// Rute untuk mengambil data GeoJSON Kecamatan (Poligon)
Route::get('/kecamatan', [PetaController::class, 'kecamatan']);

// Rute untuk mengambil data GeoJSON Kelurahan (Poligon)
Route::get('/kelurahan', [PetaController::class, 'kelurahan']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});