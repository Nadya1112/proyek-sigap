<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class InformasiController extends Controller
{
    public function index()
    {
        // Contoh data dokumen. Nanti bisa diambil dari DB.
        $docs = [
            [
                'slug'  => 'uud-no-1-2011',
                'title' => 'UUD No 1 Tahun 2011',
                'file'  => 'uud-no-1-2011.pdf',
                'ext'   => 'PDF',
                'size'  => 524, // KB
                'year'  => 2011,
            ],
            [
                'slug'  => 'perda-no-1-2023',
                'title' => 'Perda No 1 Tahun 2023',
                'file'  => 'perda-no-1-2023.pdf',
                'ext'   => 'PDF',
                'size'  => 812,
                'year'  => 2023,
            ],
            [
                'slug'  => 'uu-no-1-sk-jalan-2022',
                'title' => 'UU No 1 SK Jalan 2022',
                'file'  => 'uu-no-1-sk-jalan-2022.pdf',
                'ext'   => 'PDF',
                'size'  => 690,
                'year'  => 2022,
            ],
            [
                'slug'  => 'uu-no-1-2011',
                'title' => 'UU No 1 Tahun 2011',
                'file'  => 'uu-no-1-2011.pdf',
                'ext'   => 'PDF',
                'size'  => 476,
                'year'  => 2011,
            ],
            [
                'slug'  => 'uu-no-1-2011-rev',
                'title' => 'UU No 1 Tahun 2011 (Revisi)',
                'file'  => 'uu-no-1-2011-revisi.pdf',
                'ext'   => 'PDF',
                'size'  => 502,
                'year'  => 2011,
            ],
        ];

        return view('public.informasi', [
            'docs' => $docs,
            'total' => count($docs),
        ]);
    }

    public function download(string $slug)
    {
        // Cari doc dari daftar di index(). Produksi: ambil dari DB.
        $docs = collect([
            ['slug'=>'uud-no-1-2011','file'=>'uud-no-1-2011.pdf'],
            ['slug'=>'perda-no-1-2023','file'=>'perda-no-1-2023.pdf'],
            ['slug'=>'uu-no-1-sk-jalan-2022','file'=>'uu-no-1-sk-jalan-2022.pdf'],
            ['slug'=>'uu-no-1-2011','file'=>'uu-no-1-2011.pdf'],
            ['slug'=>'uu-no-1-2011-rev','file'=>'uu-no-1-2011-revisi.pdf'],
        ]);

        $doc = $docs->firstWhere('slug', $slug);
        if (!$doc) {
            abort(404);
        }

        // Simpan file di storage/app/public/informasi
        $path = storage_path('app/public/informasi/'.$doc['file']);
        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($path);
    }
}
