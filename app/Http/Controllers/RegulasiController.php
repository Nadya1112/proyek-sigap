<?php

namespace App\Http\Controllers;

use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

class RegulasiController extends Controller
{
    public function __construct(private GoogleDriveService $gdrive) {}

    public function index(Request $request)
    {
        // Logika pencarian dihapus dari sini karena akan ditangani oleh frontend
        $files = $this->gdrive->listFiles();

        // Urutkan berdasarkan waktu modifikasi terbaru
        usort($files, fn($a, $b) => strcmp($b['modified'] ?? '', $a['modified'] ?? ''));
        
        // Kirim semua dokumen ke view
        return view('public.regulasi', [
            'docs' => $files,
        ]);
    }

        public function download(string $id)
        {
            $url = $this->gdrive->exportDownloadUrl($id);
            return redirect()->away($url);
        }
}
