<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kompleks; // PENTING: Jangan lupa baris ini untuk memanggil Model
use Illuminate\Http\Request;

class PetaController extends Controller
{
    /**
     * Mengambil data kompleks dan memformatnya sebagai GeoJSON.
     * Ini adalah fungsi yang akan dieksekusi.
     */
    public function kompleks(Request $request)
    {
        // 1. Mengambil semua data dari tabel 'kompleks'
        $semuaKompleks = Kompleks::all();

        // 2. Mengubah koleksi data menjadi format GeoJSON Feature
        $fiturGeoJSON = $semuaKompleks->map(function ($kompleks) {
            return [
                'type' => 'Feature',
                'geometry' => $kompleks->area,
                'properties' => [
                    'nama' => $kompleks->nama_komplek,
                    'alamat' => $kompleks->alamat,
                    'status_aset' => $kompleks->status_aset,
                ]
            ];
        });

        // 3. Mengirimkan hasil akhir sebagai response JSON
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $fiturGeoJSON
        ]);
    }
}