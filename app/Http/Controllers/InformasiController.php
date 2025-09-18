<?php

namespace App\Http\Controllers;

use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function __construct(private GoogleDriveService $gdrive) {}

    public function index(Request $request)
    {
        $folderId = env('GOOGLE_DRIVE_FOLDER_ID');
        $q = trim((string) $request->get('q', ''));
        $files = $this->gdrive->listFiles($folderId, $q ?: null);

        // Urutkan: terbaru dulu
        usort($files, fn($a,$b) => strcmp($b['mtime'] ?? '', $a['mtime'] ?? ''));

        return view('public.informasi', [
            'docs' => collect($files)->map(function($f){
                // Ekstrak tahun dari nama (opsional)
                preg_match('/\b(19|20)\d{2}\b/', $f['name'], $m);
                return [
                    'id'      => $f['id'],
                    'title'   => preg_replace('/\.\w+$/','',$f['name']),
                    'year'    => isset($m[0]) ? (int) $m[0] : null,
                    'size_kb' => isset($f['size']) ? (int) round($f['size']/1024) : null,
                    'mime'    => $f['mime'] ?? null,
                    'mtime'   => $f['mtime'] ?? null,
                ];
            }),
            'q' => $q,
        ]);
    }

        public function download(string $id)
        {
            $url = $this->gdrive->exportDownloadUrl($id);
            return redirect()->away($url);
        }
}
