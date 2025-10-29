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
     */
    public function kompleks(Request $request)
    {
        // Mengambil data komplek & menggabungkannya (JOIN) dengan kelurahan dan kecamatan
        $dataPerumahan = DB::table('kompleks') // Tabel 'kompleks'
            ->join('kelurahans', 'kompleks.kelurahan_id', '=', 'kelurahans.id')
            ->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
            ->select(
                'kompleks.id',
                'kompleks.nama_komplek',
                'kompleks.pengembang', // Ambil kolom pengembang
                'kompleks.latitude',
                'kompleks.longitude',
                'kelurahans.nama_kelurahan',
                'kecamatans.nama_kecamatan'
            )
            ->whereNotNull('kompleks.latitude')
            ->whereNotNull('kompleks.longitude')
            ->get();

        // Mengubah data menjadi format GeoJSON
        $features = [];
        foreach ($dataPerumahan as $row) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$row->longitude, (float)$row->latitude]
                ],
                'properties' => [
                    // Data untuk popup Kompleks
                    'id' => $row->id,
                    'nama_perumahan' => $row->nama_komplek,
                    'nama_pengembang' => $row->pengembang ?? 'Tidak Diketahui', // Tampilkan pengembang
                    'kelurahan' => $row->nama_kelurahan,
                    'kecamatan' => $row->nama_kecamatan,
                ]
            ];
        }
        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }

    /**
     * API untuk data poligon kecamatan (Area)
     */
    public function kecamatan(Request $request)
    {
        // Mengambil data poligon dari tabel 'kecamatans'
        $dataKecamatan = DB::table('kecamatans')
                           ->whereNotNull('geojson_data') // Hanya ambil yg ada data poligonnya
                           ->get();

        $features = [];
        foreach ($dataKecamatan as $row) {

            $geometry = null;
            try {
                // Decode string JSON dari database menjadi objek
                $geometry = json_decode(trim($row->geojson_data), false, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                Log::error("Gagal decode GeoJSON Kecamatan ID {$row->id}: " . $e->getMessage()); // Opsional
            }

            // Jika datanya valid, "bungkus" ke dalam format "Feature"
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
    }

    /**
     * API untuk data poligon kelurahan (Area)
     */
    public function kelurahan(Request $request)
    {
        // Ambil data kelurahan, gabungkan dengan kecamatan
        // Pastikan tabel kelurahans memiliki kolom sumber, shape_leng, shape_area
        $dataKelurahan = DB::table('kelurahans')
                           ->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
                           ->select(
                               'kelurahans.id',
                               'kelurahans.nama_kelurahan',
                               'kelurahans.geojson_data', // Data poligon
                               'kecamatans.nama_kecamatan', // Nama kecamatan induk
                               // Asumsi kolom ini ada di tabel kelurahans:
                               DB::raw("'BANJARMASIN' as kabupaten"), // Jika selalu Banjarmasin
                               'kelurahans.sumber',         // Sesuaikan nama kolom jika beda
                               'kelurahans.shape_leng',     // Sesuaikan nama kolom jika beda
                               'kelurahans.shape_area'      // Sesuaikan nama kolom jika beda
                           )
                           ->whereNotNull('kelurahans.geojson_data') // Hanya ambil yg ada poligonnya
                           ->get();

        $features = [];
        foreach ($dataKelurahan as $row) {
            $geometry = null;
            try {
                // Decode string JSON dari database
                $geometry = json_decode(trim($row->geojson_data), false, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                Log::error("Gagal decode GeoJSON Kelurahan ID {$row->id}: " . $e->getMessage()); // Opsional
            }

            if ($geometry) {
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => $geometry,
                    'properties' => [
                        // Sesuaikan nama properti agar cocok dengan popup Anda
                        'id' => $row->id,
                        'kabupaten' => $row->kabupaten ?? 'BANJARMASIN',
                        'kecamatan' => $row->nama_kecamatan,
                        'desa' => $row->nama_kelurahan, // Menggunakan nama_kelurahan
                        'sumber' => $row->sumber ?? 'N/A',
                        'shape_leng' => round($row->shape_leng ?? 0, 8), // Format angka
                        'shape_area' => round($row->shape_area ?? 0, 8)  // Format angka
                    ]
                ];
            }
        }
        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }
}