<?php

namespace App\Http\Controllers;

use App\Models\Regulasi; // Gunakan model Regulasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegulasiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data dari database, bukan Google Drive
        $query = Regulasi::query()->orderBy('created_at', 'desc');

        if ($request->has('q') && $request->q != '') {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }
        
        $docs = $query->get();
        $q = $request->q ?? '';

        return view('public.regulasi', compact('docs', 'q'));
    }

    public function download(string $id)
        {
            // 1. Cari data regulasi di database berdasarkan ID yang diberikan
            $regulasi = \App\Models\Regulasi::find($id);

            // 2. Jika data tidak ditemukan, tampilkan halaman error 404
            if (!$regulasi) {
                abort(404, 'Dokumen tidak ditemukan.');
            }

            // 3. Jika ditemukan, gunakan path dari database untuk mengunduh file dari storage
            // Pastikan nama file yang diunduh adalah nama aslinya
            return Storage::disk('public')->download($regulasi->path, $regulasi->nama_file_asli);
        }
}