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
        // Ambil semua data kecamatan dan kelurahan untuk dropdown filter
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        $kelurahans = Kelurahan::orderBy('nama_kelurahan')->get();

        // Mulai query untuk mengambil data Komplek
        $kompleksQuery = Komplek::query()
            ->with(['kelurahan.kecamatan']) // Eager loading untuk performa
            ->latest(); // Urutkan berdasarkan yang terbaru

        // Terapkan filter pencarian jika ada
        if ($request->filled('search')) {
            $search = $request->input('search');
            $kompleksQuery->where(function ($query) use ($search) {
                $query->where('nama_komplek', 'like', "%{$search}%")
                      ->orWhere('nomor', 'like', "%{$search}%");
            });
        }

        // Terapkan filter berdasarkan Kecamatan
        if ($request->filled('kecamatan')) {
            $kompleksQuery->whereHas('kelurahan.kecamatan', function ($query) use ($request) {
                $query->where('id', $request->input('kecamatan'));
            });
        }
        
        // Terapkan filter berdasarkan Kelurahan
        if ($request->filled('kelurahan')) {
            $kompleksQuery->whereHas('kelurahan', function ($query) use ($request) {
                $query->where('id', $request->input('kelurahan'));
            });
        }

        // Ambil data dengan paginasi (10 data per halaman)
        $kompleks = $kompleksQuery->paginate(10);

        // Hitung statistik untuk ringkasan di atas tabel
        $totalKomplek = $kompleks->total();
        $sudahDiserahkan = Komplek::where('status_aset', 'Sudah Diserahkan')->count();
        $prosesPenyerahan = Komplek::where('status_aset', 'Proses Penyerahan')->count();
        $belumDiserahkan = Komplek::where('status_aset', 'Belum Diserahkan')->count();

        // Kirim semua data yang diperlukan ke view
        return view('public.informasi', [
            'kompleks' => $kompleks,
            'kecamatans' => $kecamatans,
            'kelurahans' => $kelurahans,
            'request' => $request, // Kirim request untuk mempertahankan nilai input
        ]);
    }
}