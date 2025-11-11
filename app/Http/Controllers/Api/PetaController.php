<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Kecamatan; // <-- Pastikan Anda sudah punya model ini (jalankan `php artisan make:model Kecamatan` jika belum)

class PetaController extends Controller
{
    /**
     * FUNGSI BARU: Untuk menampilkan halaman peta (peta.blade.php)
     */
    public function tampilkanPeta()
    {
        return view('peta');
    }

    /**
     * API untuk sebaran komplek perumahan (Titik/Point)
     * (Ini adalah kode Anda yang sudah ada, sudah bagus)
     */
    public function kompleks(Request $request)
    {
        try {
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
                        'nama_perumahan' => $row->nama_komplek,
                        'nama_pengembang' => 'Tidak Diketahui', // Hardcode
                        'kelurahan' => $row->nama_kelurahan,
                        'kecamatan' => $row->nama_kecamatan,
                    ]
                ];
            }
            return response()->json(['type' => 'FeatureCollection', 'features' => $features]);

        } catch (\Exception $e) {
            Log::error("Error di API /api/kompleks: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data kompleks', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API untuk data poligon kecamatan (Area)
     * (Ini adalah kode Anda yang sudah ada, sudah bagus)
     */
    public function kecamatan(Request $request)
    {
        try {
            // Pastikan nama kolom 'geometri' di tabel 'kecamatans' sudah benar
            $dataKecamatan = DB::table('kecamatans')
                ->whereNotNull('geometri')
                ->get();

            $features = [];
            foreach ($dataKecamatan as $row) {
                $geometry = null;
                try {
                    $geometry = json_decode(trim($row->geometri), false, 512, JSON_THROW_ON_ERROR);
                } catch (\JsonException $e) {
                    Log::error("Gagal decode GeoJSON Kecamatan ID {$row->id}: " . $e->getMessage());
                    continue;
                }

                if ($geometry) {
                    $features[] = [
                        'type' => 'Feature',
                        'geometry' => $geometry,
                        'properties' => [
                            'nama' => $row->nama_kecamatan,
                            'warna' => $row->warna
                        ]
                    ];
                }
            }
            return response()->json(['type' => 'FeatureCollection', 'features' => $features]);

        } catch (\Exception $e) {
            Log::error("Error di API /api/kecamatan: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data kecamatan', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * FUNGSI LAMA (kelurahan) DIGANTI DENGAN YANG BARU INI:
     *
     * API untuk data poligon kelurahan (Area)
     * Versi baru ini mengambil data asli (geojson_data, shape_leng, dll)
     * dan juga daftar kecamatan untuk filter.
     */
    public function kelurahan(Request $request)
    {
        try {
            // 1. Ambil semua data kelurahan, JOIN dengan tabel kecamatans
            $kelurahans = DB::table('kelurahans')
                ->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
                ->select(
                    'kelurahans.*', // Ambil semua dari kelurahans
                    'kecamatans.nama_kecamatan' // Ambil nama_kecamatan
                )
                ->whereNotNull('kelurahans.geojson_data') // <-- Menggunakan kolom baru kita
                ->get();

            // 2. Ambil juga daftar semua kecamatan (untuk filter di sidebar)
            $kecamatans = DB::table('kecamatans')->select('id', 'nama_kecamatan')->get();

            // 3. Ubah menjadi format GeoJSON FeatureCollection
            $fitur = [];
            foreach ($kelurahans as $data) {
                $geometri = null;
                try {
                    $geometri = json_decode(trim($data->geojson_data), false, 512, JSON_THROW_ON_ERROR); // <-- Menggunakan kolom baru kita
                } catch (\JsonException $e) {
                    Log::error("Gagal decode GeoJSON Kelurahan ID {$data->id}: " . $e->getMessage());
                    continue;
                }

                if ($geometri) {
                    $fitur[] = [
                        'type' => 'Feature',
                        'geometry' => $geometri,
                        'properties' => [
                            'nama_kelurahan' => $data->nama_kelurahan,
                            'sumber' => $data->sumber, // <-- Data asli
                            'shape_leng' => $data->shape_leng, // <-- Data asli
                            'shape_area' => $data->shape_area, // <-- Data asli
                            'kecamatan_id' => $data->kecamatan_id, // <-- Penting untuk filter
                            'nama_kecamatan' => $data->nama_kecamatan,
                        ]
                    ];
                }
            }

            $featureCollection = [
                'type' => 'FeatureCollection',
                'features' => $fitur
            ];

            // 4. Kembalikan data GeoJSON DAN daftar kecamatan
            return response()->json([
                'geojson' => $featureCollection,
                'kecamatans' => $kecamatans
            ]);

        } catch (\Exception $e) {
            Log::error("Error di API /api/kelurahan: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data kelurahan', 'message' => $e->getMessage()], 500);
        }
    }
}