<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komplek;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class FasumController extends Controller
{
    /**
     * Menampilkan halaman Informasi FASUM dengan data komplek perumahan.
     */
    public function index(Request $request)
    {
        // --- QUICK STATS (global, tidak terpengaruh filter) ---
        // Pastikan nilai status di DB: 'Sudah Diserahkan', 'Proses Penyerahan', 'Belum Diserahkan'
        $stats = Komplek::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status_aset = 'Sudah Diserahkan' THEN 1 ELSE 0 END) as sudah_diserahkan")
            ->selectRaw("SUM(CASE WHEN status_aset = 'Proses Penyerahan' THEN 1 ELSE 0 END) as proses_penyerahan")
            ->selectRaw("SUM(CASE WHEN status_aset = 'Belum Diserahkan' THEN 1 ELSE 0 END) as belum_diserahkan")
            ->first();

        // --- DATA PENDUKUNG FILTER (biar halaman tetap render walau Filter Section belum kita beresin) ---
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        // sementara: tampilkan semua kelurahan (nanti tahap 2 kita perketat sesuai kecamatan)
        $kelurahans = Kelurahan::orderBy('nama_kelurahan')->get();

        // --- LIST DATA (sementara simple; filter kita bereskan di langkah berikut) ---
        $kompleks = Komplek::with(['kelurahan.kecamatan'])
            ->orderBy('nama_komplek')
            ->paginate(10);

        return view('public.informasi', [
            'request'           => $request,
            // QUICK STATS ke Blade
            'totalKomplek'      => (int) ($stats->total ?? 0),
            'sudahDiserahkan'   => (int) ($stats->sudah_diserahkan ?? 0),
            'prosesPenyerahan'  => (int) ($stats->proses_penyerahan ?? 0),
            'belumDiserahkan'   => (int) ($stats->belum_diserahkan ?? 0),
            // Data lain
            'kecamatans'        => $kecamatans,
            'kelurahans'        => $kelurahans,
            'kompleks'          => $kompleks,
        ]);
    }
}