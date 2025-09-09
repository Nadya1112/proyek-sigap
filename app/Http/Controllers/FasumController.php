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
        // QUICK STATS (tetap global seperti sebelumnya)
        $stats = Komplek::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status_aset = 'Sudah Diserahkan' THEN 1 ELSE 0 END) as sudah_diserahkan")
            ->selectRaw("SUM(CASE WHEN status_aset = 'Proses Penyerahan' THEN 1 ELSE 0 END) as proses_penyerahan")
            ->selectRaw("SUM(CASE WHEN status_aset = 'Belum Diserahkan' THEN 1 ELSE 0 END) as belum_diserahkan")
            ->first();

        // ==== FILTER QUERY ====
        $query = Komplek::with(['kelurahan.kecamatan']);

        // Filter: Kecamatan
        if ($request->filled('kecamatan')) {
            $query->whereHas('kelurahan.kecamatan', function ($q) use ($request) {
                $q->where('id', $request->input('kecamatan'));
            });
        }

        // Filter: Kelurahan
        if ($request->filled('kelurahan')) {
            $query->where('kelurahan_id', $request->input('kelurahan'));
        }

        // Filter: Status Aset (pastikan nilai konsisten di DB)
        if ($request->filled('status')) {
            $query->where('status_aset', $request->input('status')); 
            // contoh nilai valid: 'Sudah Diserahkan', 'Proses Penyerahan', 'Belum Diserahkan'
        }

        // Filter: Search (nama komplek / alamat / nomor)
        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($w) use ($s) {
                $w->where('nama_komplek', 'like', "%{$s}%")
                  ->orWhere('alamat', 'like', "%{$s}%")
                  ->orWhere('nomor', 'like', "%{$s}%");
            });
        }

        $kompleks = $query
            ->orderBy('nama_komplek')
            ->paginate(10)
            ->appends($request->query());

        // Data dropdown kecamatan selalu lengkap
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();

        // Data dropdown kelurahan:
        // - jika ada kecamatan terpilih → hanya kelurahan di kecamatan itu
        // - jika tidak ada → (boleh kosongkan agar user dipaksa pilih kecamatan dulu)
        $kelurahans = collect();
        if ($request->filled('kecamatan')) {
            $kelurahans = Kelurahan::where('kecamatan_id', $request->input('kecamatan'))
                ->orderBy('nama_kelurahan')
                ->get();
        }

        return view('public.informasi', [
            'request'           => $request,

            // Quick Stats
            'totalKomplek'      => (int) ($stats->total ?? 0),
            'sudahDiserahkan'   => (int) ($stats->sudah_diserahkan ?? 0),
            'prosesPenyerahan'  => (int) ($stats->proses_penyerahan ?? 0),
            'belumDiserahkan'   => (int) ($stats->belum_diserahkan ?? 0),

            // Dropdown data
            'kecamatans'        => $kecamatans,
            'kelurahans'        => $kelurahans,

            // Tabel/Grid data
            'kompleks'          => $kompleks,
        ]);
    }

    // ==== Endpoint AJAX: kelurahan by kecamatan ====
    public function kelurahanByKecamatan($kecamatanId)
    {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatanId)
            ->orderBy('nama_kelurahan')
            ->get(['id', 'nama_kelurahan']);

        return response()->json($kelurahans);
    }
}