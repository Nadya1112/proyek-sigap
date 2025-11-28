<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetaController extends Controller
{
    // 1. API KOMPLEKS (TITIK)
    public function kompleks(Request $request)
    {
        try {
            // Mengambil data dari tabel 'kompleks'
            $dataPerumahan = DB::table('kompleks')
                ->join('kelurahans', 'kompleks.kelurahan_id', '=', 'kelurahans.id')
                ->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
                ->select(
                    'kompleks.id',
                    'kompleks.nama_komplek',
                    'kompleks.nama_pengembang',
                    // [PERBAIKAN UTAMA DISINI]
                    'kompleks.alamat_komplek as alamat', // Mengambil 'alamat_komplek' jadi 'alamat'
                    'kompleks.latitude',
                    'kompleks.longitude',
                    'kompleks.jumlah_sertifikat',
                    'kompleks.jumlah_unit',
                    'kompleks.status_aset',
                    'kompleks.fasilitas_ibadah',
                    'kompleks.fasilitas_umum',
                    'kompleks.fasilitas_pendidikan',
                    'kompleks.fasilitas_kesehatan',
                    'kelurahans.nama_kelurahan',
                    'kecamatans.nama_kecamatan'
                )
                ->whereNotNull('kompleks.latitude')
                ->whereNotNull('kompleks.longitude')
                ->get();

            $features = [];
            foreach ($dataPerumahan as $row) {
                $longitude = (float)$row->longitude;
                $latitude = (float)$row->latitude;

                // Validasi koordinat
                if ($longitude == 0 || $latitude == 0) continue;

                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [$longitude, $latitude]
                    ],
                    'properties' => [
                        'id' => $row->id,
                        'nama_perumahan' => $row->nama_komplek,
                        'nama_pengembang' => $row->nama_pengembang ?? '-',
                        'alamat' => $row->alamat ?? '-', 
                        'kelurahan' => $row->nama_kelurahan,
                        'kecamatan' => $row->nama_kecamatan,
                        
                        // Data Detail Lainnya
                        'jumlah_sertifikat' => $row->jumlah_sertifikat ?? 0,
                        'jumlah_unit' => $row->jumlah_unit ?? 0,
                        'status_aset' => $row->status_aset ?? '-',
                        'fasilitas_ibadah' => $row->fasilitas_ibadah ?? '-',
                        'fasilitas_umum' => $row->fasilitas_umum ?? '-',
                        'fasilitas_pendidikan' => $row->fasilitas_pendidikan ?? '-',
                        'fasilitas_kesehatan' => $row->fasilitas_kesehatan ?? '-',
                    ]
                ];
            }
            return response()->json(['type' => 'FeatureCollection', 'features' => $features]);

        } catch (\Exception $e) {
            Log::error("Error API Kompleks: " . $e->getMessage());
            return response()->json(['error' => 'Gagal', 'msg' => $e->getMessage()], 500);
        }
    }

    // 2. API KECAMATAN (POLIGON)
    public function kecamatan(Request $request)
    {
        try {
            $data = DB::table('kecamatans')->select('id', 'nama_kecamatan', 'warna', 'geometri')->get();
            $features = [];
            foreach ($data as $row) {
                if (empty($row->geometri)) continue;
                $geom = json_decode($row->geometri);
                if (json_last_error() !== JSON_ERROR_NONE) continue;
                $features[] = [
                    'type' => 'Feature', 
                    'geometry' => $geom, 
                    'properties' => ['nama' => $row->nama_kecamatan, 'warna' => $row->warna]
                ];
            }
            return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
        } catch (\Exception $e) { return response()->json(['error' => 'Gagal'], 500); }
    }

    // 3. API KELURAHAN (POLIGON)
    public function kelurahan(Request $request)
    {
        try {
            $data = DB::table('kelurahans')
                ->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
                ->select('kelurahans.id', 'kelurahans.nama_kelurahan', 'kelurahans.geometri', 'kecamatans.nama_kecamatan')
                ->get();
            $features = [];
            foreach ($data as $row) {
                if (empty($row->geometri)) continue;
                $geom = json_decode($row->geometri);
                if (json_last_error() !== JSON_ERROR_NONE) continue;
                $features[] = [
                    'type' => 'Feature', 
                    'geometry' => $geom, 
                    'properties' => ['id' => $row->id, 'nama_kelurahan' => $row->nama_kelurahan]
                ];
            }
            return response()->json(['geojson' => ['type' => 'FeatureCollection', 'features' => $features]]);
        } catch (\Exception $e) { return response()->json(['error' => 'Gagal'], 500); }
    }
}