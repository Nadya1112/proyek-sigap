<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetaController extends Controller
{
    public function tampilkanPeta()
    {
        return view('peta');
    }

    // 1. API KOMPLEKS (TITIK)
    public function kompleks(Request $request)
    {
        try {
            $dataPerumahan = DB::table('kompleks')
                ->join('kelurahans', 'kompleks.kelurahan_id', '=', 'kelurahans.id')
                ->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
                ->select(
                    'kompleks.id',
                    'kompleks.nama_komplek',
                    'kompleks.nama_pengembang',
                    'kompleks.alamat_komplek as alamat',
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
                
                try {
                    // Bersihkan format JSON jika perlu
                    $cleanGeom = trim($row->geometri);
                    if (str_starts_with($cleanGeom, '"') && str_ends_with($cleanGeom, '"')) {
                        $cleanGeom = json_decode($cleanGeom); // Decode string ganda
                    }
                    
                    $geometry = json_decode($cleanGeom);
                    if (json_last_error() !== JSON_ERROR_NONE) continue;
                    
                    $features[] = [
                        'type' => 'Feature', 
                        'geometry' => $geometry, 
                        'properties' => ['nama' => $row->nama_kecamatan, 'warna' => $row->warna]
                    ];
                } catch (\Exception $e) { continue; }
            }
            return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
        } catch (\Exception $e) { return response()->json(['error' => 'Gagal'], 500); }
    }

    // 3. API KELURAHAN (POLIGON) - [DIPERBAIKI LOGIKA DECODE-NYA]
    public function kelurahan(Request $request)
    {
        try {
            // Ambil SEMUA data
            $data = DB::table('kelurahans')
                ->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
                ->select(
                    'kelurahans.id', 
                    'kelurahans.nama_kelurahan', 
                    'kelurahans.geometri',
                    'kelurahans.sumber',
                    'kecamatans.nama_kecamatan'
                )
                ->whereNotNull('kelurahans.geometri')
                ->get(); 
            
            $features = [];
            foreach ($data as $row) {
                if (empty($row->geometri)) continue;

                try {
                    // [KUNCI PERBAIKAN]
                    // 1. Ambil string mentah
                    $rawGeom = $row->geometri;

                    // 2. Jika string diawali tanda petik ("), berarti ter-encode ganda, kita decode sekali agar jadi JSON murni
                    if (substr($rawGeom, 0, 1) === '"') {
                        $rawGeom = json_decode($rawGeom);
                    }

                    // 3. Bersihkan Backslash (Escape Character) yang mengganggu
                    $rawGeom = stripslashes($rawGeom);

                    // 4. Baru di-decode menjadi Objek
                    $geom = json_decode($rawGeom);
                    
                    // 5. Jika masih gagal, coba decode tanpa stripslashes (fallback)
                    if (json_last_error() !== JSON_ERROR_NONE) {
                         $geom = json_decode($row->geometri);
                    }

                    // Jika sukses, masukkan ke fitur
                    if (json_last_error() === JSON_ERROR_NONE && $geom) {
                        $features[] = [
                            'type' => 'Feature', 
                            'geometry' => $geom, 
                            'properties' => [
                                'id' => $row->id, 
                                'nama_kelurahan' => $row->nama_kelurahan,
                                'nama_kecamatan' => $row->nama_kecamatan,
                                'sumber' => $row->sumber
                            ]
                        ];
                    }
                } catch (\Exception $e) { 
                    continue; 
                }
            }
            return response()->json(['geojson' => ['type' => 'FeatureCollection', 'features' => $features]]);
        } catch (\Exception $e) { 
            Log::error("Error API Kelurahan: " . $e->getMessage());
            return response()->json(['error' => 'Gagal'], 500); 
        }
    }
}