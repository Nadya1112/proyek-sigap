<?php

namespace App\Http\Controllers;

use App\Models\Regulasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegulasiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mulai Query
        $query = Regulasi::query()->orderBy('created_at', 'desc');

        // 2. Filter Pencarian Judul
        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        // 3. Filter Tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // 4. Filter Jenis Dokumen
        if ($request->filled('jenis')) {
            $query->where('jenis_dokumen', $request->jenis);
        }
        
        // 5. Ambil data unik untuk dropdown filter di View
        $filterTahun = Regulasi::query()
                            ->select('tahun')
                            ->whereNotNull('tahun')
                            ->distinct()
                            ->orderBy('tahun', 'desc')
                            ->pluck('tahun');
        
        // Opsi Jenis Dokumen (Hardcoded sesuai enum/pilihan di admin)
        $filterJenis = ['Perda', 'Perkada', 'SOP', 'Lainnya']; 

        // 6. Eksekusi dengan Paginasi (5 dokumen per halaman)
        // appends($request->query()) penting agar filter tidak hilang saat pindah halaman
        $docs = $query->paginate(5)->appends($request->query());

        // 7. Kirim data ke View
        return view('public.regulasi', [
            'docs' => $docs,
            'filterTahun' => $filterTahun,
            'filterJenis' => $filterJenis,
            'input' => $request->all(), // Kirim input user agar form tidak reset
        ]);
    }

    public function download(string $id)
    {
        $regulasi = Regulasi::find($id);

        if (!$regulasi) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        // Cek keberadaan file sebelum download
        if (Storage::disk('public')->exists($regulasi->path)) {
            return Storage::disk('public')->download($regulasi->path, $regulasi->nama_file_asli);
        } else {
            return back()->with('error', 'File fisik tidak ditemukan di server.');
        }
    }
}