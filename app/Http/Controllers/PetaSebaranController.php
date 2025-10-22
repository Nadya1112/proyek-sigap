<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetaSebaranController extends Controller
{
    /**
     * Menampilkan halaman peta sebaran (sebaran.blade.php)
     */
    public function index()
    {
        // Mengambil data untuk panel filter/legenda
        // Menggunakan nama tabel 'kecamatans' (plural)
        $kecamatans = DB::table('kecamatans') 
                        ->select('nama_kecamatan', 'warna') // Mengambil kolom nama & warna
                        ->get();

        // Mengirim data 'kecamatans' ke file view 'public.sebaran'
        return view('public.sebaran', [
            'kecamatans' => $kecamatans
        ]);
    }
}