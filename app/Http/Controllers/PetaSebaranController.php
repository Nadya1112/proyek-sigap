<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Komplek;

class PetaSebaranController extends Controller
{
    public function index()
    {
        $kecamatans = DB::table('kecamatans')->select('id', 'nama_kecamatan', 'warna')->get();

        return view('public.sebaran', [ 
            'kecamatans' => $kecamatans
        ]);
    }

    // --- FUNGSI API (VERSI SUDAH DIPERBAIKI) ---
    public function kompleks()
    {
        $kompleks = Komplek::all();

        $geoJSON = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($kompleks as $komplek) {
            
            $latRaw = $komplek->latitude;
            $lngRaw = $komplek->longitude;

            // 1. Ubah koma jadi titik jika ada
            $latRaw = str_replace(',', '.', trim($latRaw));
            $lngRaw = str_replace(',', '.', trim($lngRaw));
            
            // 2. Konversi ke float
            $lat = (float) $latRaw;
            $lng = (float) $lngRaw;

            // 3. Cek Validasi Dasar
            if (empty($lat) || empty($lng) || $lat == 0 || $lng == 0) {
                continue; 
            }

            // [PERBAIKAN UTAMA] Normalisasi Koordinat
            // Memperbaiki angka integer besar (misal: -3300665388 menjadi -3.3...)
            while (abs($lat) > 90) {
                $lat /= 10;
            }
            while (abs($lng) > 180) {
                $lng /= 10;
            }

            $geoJSON['features'][] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$lng, $lat], // Urutan: Longitude, Latitude
                ],
                'properties' => $komplek->toArray() 
            ];
        }

        return response()->json($geoJSON);
    }
}