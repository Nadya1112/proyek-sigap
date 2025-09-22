<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan; // Import model Pengaduan

class PengaduanController extends Controller
{
    /**
     * Menampilkan form pengaduan beserta data statistik.
     */
    public function showPengaduanForm()
    {
        // Menghitung statistik pengaduan
        $stats = [
            'total'   => Pengaduan::count(),
            'diterima' => Pengaduan::where('status', 'Diterima')->count(),
            'proses'  => Pengaduan::where('status', 'Diproses')->count(),
            'selesai' => Pengaduan::where('status', 'Selesai')->count(),
        ];

        // Mengirim data statistik ke view 'public.pengaduan'
        return view('public.pengaduan', $stats);
    }

    /**
     * Menyimpan data pengaduan baru dari form.
     */
    public function storePengaduan(Request $request)
    {
        // Logika untuk menyimpan pengaduan (kode dari sebelumnya)
        // Pastikan Anda sudah mengimpor model dan kelas lainnya jika diperlukan.
        // Contoh: use App\Models\Pengaduan; use Illuminate\Support\Facades\Auth;
        // Kode ini tidak saya lampirkan karena sudah ada di PublicFormController.php
    }
}