<?php

namespace App\Http\Controllers\Api; // <-- Pastikan namespace benar

use App\Http\Controllers\Controller; // <-- Pastikan use statement benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Pastikan use statement benar
use Illuminate\Support\Facades\Log; // Optional: Untuk logging error

class PetaController extends Controller
{
    /**
     * API untuk sebaran komplek perumahan (Titik/Point)
     * PERBAIKAN: Menghapus 'kompleks.pengembang' dari query
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
                    // 'kompleks.pengembang', // <-- DIHAPUS, ini yang menyebabkan error 500
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
                        'nama_pengembang' => 'Tidak Diketahui', // <-- Di-hardcode karena kolom tidak ada
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
     * PERBAIKAN: Mengganti 'geojson_data' menjadi 'polygon'.
     */
    public function kecamatan(Request $request)
    {
        try {
            $dataKecamatan = DB::table('kecamatans')
                                ->whereNotNull('polygon') // <-- DIUBAH dari geojson_data
                                ->get(); 

            $features = [];
            foreach ($dataKecamatan as $row) {
                $geometry = null;
                try {
                    $geometry = json_decode(trim($row->polygon), false, 512, JSON_THROW_ON_ERROR); // <-- DIUBAH dari geojson_data
                } catch (\JsonException $e) {
                    Log::error("Gagal decode GeoJSON Kecamatan ID {$row->id}: " . $e->getMessage()); 
                    continue; // Lanjut ke data berikutnya jika 1 data rusak
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
     * PERBAIKAN: Menghapus kolom 'sumber', 'shape_leng', 'shape_area'
     */
    public function kelurahan(Request $request)
    {
        try {
            $dataKelurahan = DB::table('kelurahans')
                                ->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
                                ->select(
                                    'kelurahans.id',
                                    'kelurahans.nama_kelurahan',
                                    'kelurahans.polygon', // <-- DIUBAH dari geojson_data
                                    'kecamatans.nama_kecamatan', 
                                    DB::raw("'BANJARMASIN' as kabupaten")
                                    // Kolom-kolom ini dihapus untuk menghindari error
                                    // 'kelurahans.sumber',
                                    // 'kelurahans.shape_leng',
                                    // 'kelurahans.shape_area'
                                )
                                ->whereNotNull('kelurahans.polygon') // <-- DIUBAH dari geojson_data
                                ->get();

            $features = [];
            foreach ($dataKelurahan as $row) {
                $geometry = null;
                try {
                    $geometry = json_decode(trim($row->polygon), false, 512, JSON_THROW_ON_ERROR); // <-- DIUBAH dari geojson_data
                } catch (\JsonException $e) {
                    Log::error("Gagal decode GeoJSON Kelurahan ID {$row->id}: " . $e->getMessage());
                    continue; // Lanjut ke data berikutnya jika 1 data rusak
                }

                if ($geometry) {
                    $features[] = [
                        'type' => 'Feature',
                        'geometry' => $geometry,
                        'properties' => [
                            'id' => $row->id,
                            'kabupaten' => $row->kabupaten ?? 'BANJARMASIN',
                            'kecamatan' => $row->nama_kecamatan,
                            'desa' => $row->nama_kelurahan,
                            'sumber' => 'N/A', // <-- Dihardcode agar tidak error
                            'shape_leng' => 0, // <-- Dihardcode agar tidak error
                            'shape_area' => 0  // <-- Dihardcode agar tidak error
                        ]
                    ];
                }
            }
            return response()->json(['type' => 'FeatureCollection', 'features' => $features]);

        } catch (\Exception $e) {
            Log::error("Error di API /api/kelurahan: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data kelurahan', 'message' => $e->getMessage()], 500);
        }
    }
}