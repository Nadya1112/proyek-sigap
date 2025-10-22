<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetaController extends Controller
{
    /**
     * API untuk sebaran komplek perumahan (Titik/Point)
     */
    public function kompleks(Request $request)
    {
        // Kode ini mengambil data 'kompleks' dan sudah benar
        $dataPerumahan = DB::table('kompleks')
            ->join('kelurahans', 'kompleks.kelurahan_id', '=', 'kelurahans.id')
            ->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
            ->select(
                'kompleks.id',
                'kompleks.nama_komplek',
                'kompleks.latitude',
                'kompleks.longitude',
                'kelurahans.nama_kelurahan',
                'kecamatans.nama_kecamatan'
            )
            ->whereNotNull('kompleks.latitude')
            ->whereNotNull('kompleks.longitude')
            ->get();

        $features = [];
        foreach ($dataPerumahan as $row) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$row->longitude, (float)$row->latitude]
                ],
                'properties' => [
                    'id' => $row->id,
                    'nama' => $row->nama_komplek,
                    'kelurahan' => $row->nama_kelurahan,
                    'kecamatan' => $row->nama_kecamatan,
                ]
            ];
        }
        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }

    /**
     * API untuk data poligon kecamatan (Area)
     * INI ADALAH FUNGSI YANG HARUS DIPERBAIKI
     */
    public function kecamatan(Request $request)
    {
        // 1. Ambil SEMUA kecamatan yang memiliki data poligon
        $dataKecamatan = DB::table('kecamatans') 
                           ->whereNotNull('geojson_data')
                           ->get(); 

        $features = []; // Buat array kosong untuk "features"

        // 2. Looping setiap kecamatan
        foreach ($dataKecamatan as $row) {
            
            $geometry = null;
            try {
                // Decode string JSON dari database menjadi objek
                $geometry = json_decode(trim($row->geojson_data), false, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                // Abaikan jika data JSON-nya rusak
            }

            // 3. Jika datanya valid, "bungkus" ke dalam format "Feature"
            if ($geometry) {
                $features[] = [
                    'type' => 'Feature', // <-- Ini adalah "Feature"
                    'geometry' => $geometry, // <-- Ini data poligon Anda
                    'properties' => [ // <-- Ini data pendukungnya
                        'nama' => $row->nama_kecamatan,
                        'warna' => $row->warna
                    ]
                ];
            }
        }
        
        // 4. "Bungkus" semua "Feature" ke dalam "FeatureCollection"
        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }
}