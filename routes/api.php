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

// API untuk Data Peta (Kompleks)
Route::get('/kompleks', function () {
    try {
        $kompleks = Komplek::all();
        
        // Format GeoJSON
        $features = $kompleks->map(function($k) {
            // Pastikan koordinat valid
            $lat = (float) str_replace(',', '.', trim($k->latitude));
            $lng = (float) str_replace(',', '.', trim($k->longitude));
            
            // Skip jika koordinat tidak valid
            if ($lat == 0 || $lng == 0) {
                return null;
            }
            
            // NORMALISASI KOORDINAT
            // Koordinat asli tersimpan sebagai integer besar (tanpa titik desimal)
            // Contoh: -3.300665388 tersimpan sebagai -3300665388
            //         114.5952953 tersimpan sebagai 1145952953
            
            // Normalisasi latitude (untuk Indonesia: -11 sampai 6)
            // Untuk Banjarmasin: sekitar -3.3
            while (abs($lat) > 11) {
                $lat /= 10;
            }
            
            // Normalisasi longitude (untuk Indonesia: 95 sampai 141)
            // Untuk Banjarmasin: sekitar 114.6
            while ($lng > 141) {
                $lng /= 10;
            }
            
            // Siapkan properties dengan koordinat yang sudah dinormalisasi
            $properties = $k->toArray();
            $properties['latitude'] = $lat;
            $properties['longitude'] = $lng;
            
            return [
                'type' => 'Feature',
                'properties' => $properties,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$lng, $lat] // Urutan: [Lng, Lat]
                ]
            ];
        })->filter()->values(); // Hapus nilai null dan reset index

        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Gagal memuat data kompleks',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Rute untuk mengambil data GeoJSON Kecamatan (Poligon)
Route::get('/kecamatan', [PetaController::class, 'kecamatan']);

// Rute untuk mengambil data GeoJSON Kelurahan (Poligon)
Route::get('/kelurahan', [PetaController::class, 'kelurahan']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});