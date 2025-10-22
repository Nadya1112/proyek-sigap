<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- PENTING: Untuk mengakses database

class PetaController extends Controller
{
    /**
     * Mengambil data sebaran komplek perumahan.
     */
    public function kompleks(Request $request)
    {
        // 1. Ambil semua data dari tabel 'komplek_perumahan'
        $dataPerumahan = DB::table('komplek_perumahan')->get();

        // 2. Siapkan array kosong untuk menampung data GeoJSON
        $features = [];

        // 3. Looping setiap baris data dan ubah formatnya
        foreach ($dataPerumahan as $row) {
            // Pastikan data latitude & longitude ada dan valid
            if ($row->latitude && $row->longitude) {
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        // Format GeoJSON adalah [longitude, latitude]
                        'coordinates' => [
                            (float)$row->longitude, // Ambil dari kolom longitude
                            (float)$row->latitude   // Ambil dari kolom latitude
                        ]
                    ],
                    // Properti ini yang akan muncul di popup
                    'properties' => [
                        'id' => $row->id,
                        'kelurahan' => $row->kelurahan,
                        'kecamatan' => $row->kecamatan,
                        'sumber' => $row->sumber,
                    ]
                ];
            }
        }

        // 4. Bungkus semua fitur dalam satu 'FeatureCollection'
        $geoJsonData = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];

        // 5. Kembalikan sebagai respons JSON
        return response()->json($geoJsonData);
    }

    /**
     * Mengambil data poligon kecamatan.
     */
    public function kecamatan(Request $request)
    {
        // TODO: Anda bisa mengisi ini nanti.
        // Logikanya bisa mengambil dari file GeoJSON statis
        // atau dari tabel database yang menyimpan poligon.
        
        // Contoh jika mengambil dari file GeoJSON statis:
        // $path = public_path('data/kecamatan.geojson');
        // if (!file_exists($path)) {
        //     return response()->json(['error' => 'File not found'], 404);
        // }
        // $data = json_decode(file_get_contents($path));
        // return response()->json($data);
    }
}