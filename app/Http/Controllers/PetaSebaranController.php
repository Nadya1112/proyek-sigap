<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan ini ada

class PetaController extends Controller
{
    /**
     * API untuk sebaran komplek perumahan (Titik/Point)
     */
    public function kompleks(Request $request)
    {
        // Nama tabel 'komplek_perumahan' sepertinya sudah benar
        $dataPerumahan = DB::table('komplek_perumahan')->get(); 

        $features = [];
        foreach ($dataPerumahan as $row) {
            if ($row->latitude && $row->longitude) {
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float)$row->longitude, (float)$row->latitude]
                    ],
                    'properties' => [
                        'id' => $row->id,
                        // Gunakan nama kolom 'kelurahan' dan 'kecamatan' persis
                        'kelurahan' => $row->kelurahan,
                        'kecamatan' => $row->kecamatan,
                        'sumber' => $row->sumber,
                    ]
                ];
            }
        }
        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }

    /**
     * API untuk data poligon kecamatan (Area)
     */
    public function kecamatan(Request $request)
    {
        // Menggunakan 'kecamatan' (bukan 'kecamatans')
        $dataKecamatan = DB::table('kecamatan') // <-- NAMA TABEL SUDAH DIPERBAIKI
                           ->get(); 

        $features = [];
        foreach ($dataKecamatan as $row) {
            // Asumsi Anda punya kolom 'geojson_data', 'nama_kecamatan', dan 'warna'
            $geometry = json_decode($row->geojson_data); 

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
}