<?php
// App\Http\Controllers\PetaSebaranController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class PetaSebaranController extends Controller
{
    public function index()
    {
        // Ambil data kecamatan, termasuk 'warna' untuk sidebar.
        $kecamatans = DB::table('kecamatans')->select('id', 'nama_kecamatan', 'warna')->get();

        // Kirim data ke view sebaran.blade.php
        return view('public.sebaran', [ 
            'kecamatans' => $kecamatans
        ]);
    }
}