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
        $data = DB::table('kelurahans')
            ->leftJoin('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
            ->select('kelurahans.*', 'kecamatans.nama_kecamatan')
            ->whereNotNull('kelurahans.geometri')->get();
        
        $features = [];
        foreach ($data as $row) {
            if (empty($row->geometri)) continue;
            try {
                $raw = trim($row->geometri, '"');
                $raw = stripslashes($raw);
                $geo = json_decode($raw);

                if (json_last_error() === JSON_ERROR_NONE && $geo) {
                    $features[] = ['type' => 'Feature', 'geometry' => $geo, 'properties' => ['nama_kelurahan' => $row->nama_kelurahan, 'nama_kecamatan' => $row->nama_kecamatan, 'sumber' => $row->sumber]];
                }
            } catch (\Exception $e) {}
        }
        return response()->json(['geojson' => ['type' => 'FeatureCollection', 'features' => $features]]);
    }
}