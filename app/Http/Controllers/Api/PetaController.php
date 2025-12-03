<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetaController extends Controller
{
    // 1. API KOMPLEKS (TITIK SEBARAN)
    public function kompleks(Request $request)
    {
        try {
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
                // Bersihkan format angka
                $lat = (float) str_replace(',', '.', trim($row->latitude));
                $lng = (float) str_replace(',', '.', trim($row->longitude));

                if ($lat == 0 || $lng == 0) continue;

                // AUTO-FIX: Ubah angka miliaran jadi desimal
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
                        'alamat' => $row->alamat_komplek ?? '-', 
                        'kelurahan' => $row->nama_kelurahan ?? '-',
                        'kecamatan' => $row->nama_kecamatan ?? '-',
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
            return response()->json(['error' => 'Gagal'], 500);
        }
    }

    // 2. API KECAMATAN (POLIGON)
    public function kecamatan() {
        $data = DB::table('kecamatans')->get();
        $features = [];
        foreach ($data as $row) {
            if (empty($row->geometri)) continue;
            try {
                // Decode JSON dengan aman
                $clean = trim($row->geometri, '"'); // Hapus kutip luar
                $clean = stripslashes($clean);      // Hapus backslash
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
            
            Log::info("Kelurahan Query: Found {$data->count()} rows with geometri");
            
            $features = [];
            foreach ($data as $row) {
                $raw = $row->geometri;
                if (empty($raw)) {
                    Log::warning("Row {$row->id}: geometri is empty");
                    continue;
                }
                try {
                    if (is_string($raw)) {
                        $raw = trim($raw, '"');
                        $raw = stripslashes($raw);
                        $raw = preg_replace('/[\r\n\t ]+/', '', $raw);
                    }
                    $geo = @json_decode($raw, false);
                    $jsonErr = json_last_error();
                    if ($jsonErr === JSON_ERROR_NONE && $geo) {
                        // Jika hasil decode adalah objek Polygon langsung
                        if (isset($geo->type) && $geo->type === 'Polygon' && isset($geo->coordinates)) {
                            $features[] = [
                                'type' => 'Feature',
                                'geometry' => $geo,
                                'properties' => [
                                    'id' => $row->id,
                                    'nama_kelurahan' => $row->nama_kelurahan ?? '-',
                                    'nama_kecamatan' => $row->nama_kecamatan ?? '-',
                                    'sumber' => $row->sumber ?? '-'
                                ]
                            ];
                        }
                        // Jika FeatureCollection
                        elseif (isset($geo->type) && $geo->type === 'FeatureCollection' && isset($geo->features) && is_array($geo->features)) {
                            foreach ($geo->features as $f) {
                                if (isset($f->geometry) && isset($f->geometry->type) && $f->geometry->type === 'Polygon') {
                                    $features[] = [
                                        'type' => 'Feature',
                                        'geometry' => $f->geometry,
                                        'properties' => [
                                            'id' => $row->id,
                                            'nama_kelurahan' => $row->nama_kelurahan ?? '-',
                                            'nama_kecamatan' => $row->nama_kecamatan ?? '-',
                                            'sumber' => $row->sumber ?? '-'
                                        ]
                                    ];
                                    break;
                                }
                            }
                        }
                        // Jika array campuran: cek Feature Polygon dan geometry Polygon langsung
                        elseif (is_array($geo)) {
                            foreach ($geo as $f) {
                                // Jika elemen array adalah geometry Polygon langsung
                                if (isset($f->type) && $f->type === 'Polygon' && isset($f->coordinates)) {
                                    $features[] = [
                                        'type' => 'Feature',
                                        'geometry' => $f,
                                        'properties' => [
                                            'id' => $row->id,
                                            'nama_kelurahan' => $row->nama_kelurahan ?? '-',
                                            'nama_kecamatan' => $row->nama_kecamatan ?? '-',
                                            'sumber' => $row->sumber ?? '-'
                                        ]
                                    ];
                                    break;
                                }
                                // Jika elemen array adalah Feature dengan geometry Polygon
                                if (isset($f->type) && $f->type === 'Feature' && isset($f->geometry) && isset($f->geometry->type) && $f->geometry->type === 'Polygon') {
                                    $features[] = [
                                        'type' => 'Feature',
                                        'geometry' => $f->geometry,
                                        'properties' => [
                                            'id' => $row->id,
                                            'nama_kelurahan' => $row->nama_kelurahan ?? '-',
                                            'nama_kecamatan' => $row->nama_kecamatan ?? '-',
                                            'sumber' => $row->sumber ?? '-'
                                        ]
                                    ];
                                    break;
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Silent catch
                }
            }
            Log::info("Kelurahan API: Total {success: " . count($features) . ", total_rows: " . $data->count() . "}");
            return response()->json([
                'geojson' => [
                    'type' => 'FeatureCollection',
                    'features' => $features
                ],
                'total_data' => $data->count(),
                'total_features' => count($features)
            ]);
        } catch (\Exception $e) {
            Log::error("Kelurahan API Error: " . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat kelurahan', 'message' => $e->getMessage()], 500);
        }
    }
}