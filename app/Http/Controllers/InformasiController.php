<?php
namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InformasiController extends Controller
{
    // Daftar + pencarian
    public function index(Request $request)
    {
        $q = trim($request->get('q',''));

        $docs = Document::when($q, function($query) use ($q) {
                    $query->where('title','like',"%{$q}%")
                          ->orWhere('year',$q);
                })
                ->orderByDesc('year')
                ->orderBy('title')
                ->get();

        $stats = [
            'total' => $docs->count(),
            // kalau mau tampil di hero
        ];

        return view('public.informasi', compact('docs','q','stats'));
    }

    // Unduhan file publik
    public function download(string $slug)
    {
        $doc = Document::where('slug',$slug)->firstOrFail();

        $path = "informasi/{$doc->filename}";
        abort_unless(Storage::disk('public')->exists($path), 404, 'File tidak ditemukan.');

        $ext = pathinfo($doc->filename, PATHINFO_EXTENSION);
        $downloadName = str($doc->title)->slug('-')->append(".{$ext}");

        Log::info('document_download', ['slug'=>$slug, 'ip'=>request()->ip()]);

        return Storage::disk('public')->download($path, $downloadName);
    }
}


