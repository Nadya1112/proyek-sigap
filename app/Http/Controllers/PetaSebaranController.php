<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Komplek; // Pastikan Model ini sudah ada

class PetaSebaranController extends Controller
{
    // 1. Fungsi untuk Membuka Halaman Web
    public function index()
    {
        $kecamatans = DB::table('kecamatans')->select('id', 'nama_kecamatan', 'warna')->get();

        return view('public.sebaran', [ 
            'kecamatans' => $kecamatans
        ]);
    }

    // 2. Fungsi API Data Komplek (INI YANG PENTING AGAR DATA TIDAK KOSONG)
    public function kompleks()
    {
        // Ambil semua data dari database
        $kompleks = Komplek::all();

        $geoJSON = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($kompleks as $komplek) {
            $geoJSON['features'][] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $komplek->longitude, 
                        (float) $komplek->latitude
                    ],
                ],
                // ============================================================
                // KUNCI PERBAIKAN: Gunakan toArray()
                // ============================================================
                // Dengan ini, SEMUA kolom di database (alamat_komplek, jumlah_unit, 
                // fasilitas_ibadah, dll) akan otomatis terkirim ke peta.
                // Tidak perlu ditulis manual satu per satu.
                'properties' => $komplek->toArray() 
            ];
        }

        return response()->json($geoJSON);
    }
}