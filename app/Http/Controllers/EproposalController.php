<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Komplek;
use App\Models\Kecamatan;
use App\Rules\ContainsKomplekOrPerumahan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EproposalController extends Controller
{
    public function showForm()
    {
        $stats = [
            'total'         => Proposal::count(),
            'diajukan'       => Proposal::where('status', 'Diajukan')->count(),
            'diverifikasi'  => Proposal::where('status', 'Diverifikasi')->count(),
            'disetujui'     => Proposal::where('status', 'Disetujui')->count(),
        ];

        $kecamatans = [];
        if (auth()->check()) {
            $kecamatans = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();
        }

        return view('public.eproposal', compact('stats', 'kecamatans'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengajukan proposal.');
        }

        $validated = $request->validate([
            'nama_pengaju'   => 'required|string|max:255',
            'kontak_pengaju' => 'required|string|max:20',
            'kecamatan_id'   => 'required|exists:kecamatans,id',
            'kelurahan_id'   => 'required|exists:kelurahans,id',
            'nama_perumahan' => ['required', 'string', 'max:255', new ContainsKomplekOrPerumahan],
            'alamat'         => 'required|string',
            'proposal'       => 'required|file|mimes:pdf,doc,docx|max:10240',
            'catatan'        => 'nullable|string',
        ]);

        // LOGIKA PENCARIAN & PEMBUATAN KOMPLEK YANG LEBIH BAIK
        // Menghapus spasi berlebih dan membuat huruf kapital di awal setiap kata
        $cleanNamaKomplek = Str::title(Str::squish($validated['nama_perumahan']));
        
        $komplek = Komplek::firstOrCreate(
            // Cari berdasarkan nama yang sudah "dibersihkan" dan kelurahan
            ['nama_komplek' => $cleanNamaKomplek, 'kelurahan_id' => $validated['kelurahan_id']],
            // Jika tidak ada, buat baru dengan data ini
            [
                'kecamatan_id' => $validated['kecamatan_id'],
                'status_aset'  => 'Belum Diserahkan',
            ]
        );

        $filePath = $request->file('proposal')->store('proposals', 'public');

        Proposal::create([
            'nama_pengaju'   => $validated['nama_pengaju'],
            'kontak_pengaju' => $validated['kontak_pengaju'],
            'kompleks_id'    => $komplek->id,
            'nama_perumahan' => $cleanNamaKomplek,
            'alamat'         => $validated['alamat'],
            'proposal'       => $filePath,
            'catatan'        => $validated['catatan'],
            'user_id'        => Auth::id(),
            'status'         => 'Diajukan',
        ]);

        return redirect()->back()->with('success', 'Proposal Anda berhasil dikirim! Terima kasih.');
    }

    /**
     * METHOD DIPERBARUI: Untuk mencari nama komplek dan menyertakan data lokasinya.
     */
    public function searchKompleks(Request $request)
    {
        $query = Str::squish($request->get('q'));
        if (!$query || strlen($query) < 2) { // Dibuat lebih responsif
            return response()->json([]);
        }

        // Cari komplek dan ambil data lokasinya melalui relasi
        $kompleks = Komplek::where('nama_komplek', 'LIKE', "%{$query}%")
            ->with('kelurahan:id,kecamatan_id') // Eager load relasi
            ->limit(5)
            ->get(['id', 'nama_komplek', 'kelurahan_id']);

        // Hilangkan duplikasi berdasarkan nama
        $uniqueKompleks = $kompleks->unique('nama_komplek')->values();

        return response()->json($uniqueKompleks);
    }
}