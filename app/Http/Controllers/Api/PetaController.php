<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Kecamatan; 

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
     * PERBAIKAN FINAL: Menggunakan pembagi yang berbeda untuk Lat (10^9) dan Lng (10^7).
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
                // === PERBAIKAN KOORDINAT FINAL DIMULAI DI SINI ===
                // Longitude (sekitar 114.xxx): Dibagi 10^7
                $longitude = (float)$row->longitude / 10000000; 
                // Latitude (sekitar -3.xxx): Dibagi 10^9
                $latitude = (float)$row->latitude / 1000000000; 
                // === PERBAIKAN KOORDINAT FINAL SELESAI DI SINI ===
                
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        // Urutan GeoJSON: [Longitude, Latitude]
                        'coordinates' => [$longitude, $latitude] 
                    ],
                    'properties' => [
                        'id' => $row->id,
                        'nama_perumahan' => $row->nama_komplek,
                        'nama_pengembang' => 'Tidak Diketahui', 
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
     */
    public function kecamatan(Request $request)
    {
        try {
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
     * API untuk data poligon kelurahan (Area)
     */
    public function kelurahan(Request $request)
    {
        try {
            $kelurahans = DB::table('kelurahans')
                ->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
                ->select(
                    'kelurahans.*', 
                    'kecamatans.nama_kecamatan' 
                )
                ->whereNotNull('kelurahans.geometri') 
                ->get();

            $kecamatans = DB::table('kecamatans')->select('id', 'nama_kecamatan')->get();

            $fitur = [];
            foreach ($kelurahans as $data) {
                $geometri = null;
                try {
                    $geometri = json_decode(trim($data->geometri), false, 512, JSON_THROW_ON_ERROR); 
                } catch (\JsonException $e) {
                    Log::error("Gagal decode GeoJSON Kelurahan ID {$data->id}: " . $e->getMessage());
                    continue;
                }

                if ($geometri) {
                    $fitur[] = [
                        'type' => 'Feature',
                        'geometry' => $geometri,
                        'properties' => [
                            'id' => $data->id, 
                            'nama_kelurahan' => $data->nama_kelurahan,
                            'sumber' => $data->sumber, 
                            'shape_leng' => $data->shape_leng, 
                            'shape_area' => $data->shape_area, 
                            'kecamatan_id' => $data->kecamatan_id, 
                            'nama_kecamatan' => $data->nama_kecamatan,
                        ]
                    ];
                }
            }

            $featureCollection = [
                'type' => 'FeatureCollection',
                'features' => $fitur
            ];

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