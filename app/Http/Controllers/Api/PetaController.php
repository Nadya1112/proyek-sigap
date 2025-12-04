<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetaController extends Controller
{
    // 1. API KOMPLEKS (TITIK SEBARAN)
    public function kompleks(Request $request)
    {
        try {
            // JOIN TABEL: Komplek + Kelurahan + Kecamatan
            $data = DB::table('kompleks')
                ->leftJoin('kelurahans', 'kompleks.kelurahan_id', '=', 'kelurahans.id')
                ->leftJoin('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
                ->select(
                    'kompleks.*', 
                    'kelurahans.nama_kelurahan',
                    'kecamatans.nama_kecamatan'
                )
                ->whereNotNull('kompleks.latitude')
                ->whereNotNull('kompleks.longitude')
                ->get();

            $features = [];

            foreach ($data as $row) {
                // 1. Bersihkan Koordinat
                $lat = (float) str_replace(',', '.', trim($row->latitude));
                $lng = (float) str_replace(',', '.', trim($row->longitude));

                if ($lat == 0 || $lng == 0) continue;

                // 2. Normalisasi Koordinat (Fix jika formatnya jutaan)
                while (abs($lat) > 10) { $lat /= 10; } 
                while (abs($lng) > 180) { $lng /= 10; }

                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [$lng, $lat]
                    ],
                    'properties' => [
                        'id' => $row->id,
                        'nama_perumahan' => $row->nama_komplek,
                        'nama_pengembang' => $row->nama_pengembang ?? '-',
                        
                        // PERBAIKAN: Sesuaikan dengan nama kolom di SQL Anda (alamat_komplek)
                        'alamat' => $row->alamat_komplek ?? '-', 
                        
                        // PERBAIKAN: Data Relasi
                        'nama_kelurahan' => $row->nama_kelurahan ?? '-', 
                        'nama_kecamatan' => $row->nama_kecamatan ?? '-',
                        
                        // Data Angka
                        'jumlah_sertifikat' => $row->jumlah_sertifikat ?? '-',
                        'jumlah_unit' => $row->jumlah_unit ?? '-', 
                        
                        'status_aset' => $row->status_aset ?? '-',
                        
                        // Fasilitas
                        'fasilitas_ibadah' => $row->fasilitas_ibadah ?? '-',
                        'fasilitas_umum' => $row->fasilitas_umum ?? '-',
                        'fasilitas_pendidikan' => $row->fasilitas_pendidikan ?? '-',
                        'fasilitas_kesehatan' => $row->fasilitas_kesehatan ?? '-',
                    ]
                ];
            }
            return response()->json(['type' => 'FeatureCollection', 'features' => $features]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal', 'message' => $e->getMessage()], 500);
        }
    }

    // 2. API KECAMATAN (POLIGON)
    public function kecamatan() {
        $data = DB::table('kecamatans')->get();
        $features = [];
        foreach ($data as $row) {
            if (empty($row->geometri)) continue;
            try {
                $clean = trim($row->geometri, '"');
                $clean = stripslashes($clean);
                $geo = json_decode($clean);
                if (json_last_error() === JSON_ERROR_NONE && $geo) {
                    $features[] = ['type' => 'Feature', 'geometry' => $geo, 'properties' => ['nama' => $row->nama_kecamatan, 'warna' => $row->warna]];
                }
            } catch (\Exception $e) {}
        }
        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }

    // 3. API KELURAHAN (POLIGON)
    public function kelurahan() {
        try {
            $data = DB::table('kelurahans')
                ->leftJoin('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
                ->select('kelurahans.id', 'kelurahans.nama_kelurahan', 'kelurahans.geometri', 'kelurahans.sumber', 'kecamatans.nama_kecamatan')
                ->whereNotNull('kelurahans.geometri')
                ->where('kelurahans.geometri', '!=', '')
                ->orderBy('kelurahans.id')
                ->get();
            
            $features = [];
            foreach ($data as $row) {
                $raw = $row->geometri;
                if (empty($raw)) continue;
                try {
                    if (is_string($raw)) {
                        $raw = trim($raw, '"');
                        $raw = stripslashes($raw);
                        $raw = preg_replace('/[\r\n\t ]+/', '', $raw);
                    }
                    $geo = @json_decode($raw, false);
                    if (json_last_error() === JSON_ERROR_NONE && $geo) {
                         $geoms = [];
                         if (isset($geo->type) && $geo->type === 'Polygon') $geoms[] = $geo;
                         elseif (isset($geo->type) && $geo->type === 'FeatureCollection' && isset($geo->features)) {
                             foreach ($geo->features as $f) if (isset($f->geometry)) $geoms[] = $f->geometry;
                         } elseif (is_array($geo)) {
                             foreach ($geo as $f) if (isset($f->type) && $f->type === 'Polygon') $geoms[] = $f;
                         }

                         foreach($geoms as $g) {
                            $features[] = [
                                'type' => 'Feature',
                                'geometry' => $g,
                                'properties' => [
                                    'id' => $row->id,
                                    'nama_kelurahan' => $row->nama_kelurahan ?? '-',
                                    'nama_kecamatan' => $row->nama_kecamatan ?? '-',
                                    'sumber' => $row->sumber ?? '-'
                                ]
                            ];
                         }
                    }
                } catch (\Exception $e) {}
            }
            return response()->json(['geojson' => ['type' => 'FeatureCollection', 'features' => $features], 'total_data' => $data->count()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal'], 500);
        }
    }
}