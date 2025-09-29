<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal; // Pastikan model Proposal ada dan benar
use App\Models\Komplek; // Pastikan model Komplek ada dan benar
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EproposalController extends Controller
{
    /**
     * Menampilkan form E-Proposal beserta data statistik.
     */
    public function showForm()
    {
        // Logika untuk mengambil statistik (sesuaikan status jika perlu)
        $stats = [
            'total'      => Proposal::count(),
            'diverifikasi' => Proposal::where('status', 'diverifikasi')->count(),
            'diproses'   => Proposal::where('status', 'diproses')->count(),
            'terkirim'   => Proposal::where('status', 'terkirim')->count(),
        ];

        // 2. AMBIL SEMUA DATA KECAMATAN DARI DATABASE
        $kecamatans = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();

        // 3. KIRIM DATA STATS DAN KECAMATANS KE VIEW
        return view('public.eproposal', compact('stats', 'kecamatans'));
        // return view('public.eproposal', $stats);
    }

    /**
     * Menyimpan data proposal baru dari form.
     */
     public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengajukan proposal.');
        }

        // 1. Validasi input, termasuk kelurahan_id dari dropdown
        $validated = $request->validate([
            'nama_pengaju'   => 'required|string|max:255',
            'kontak_pengaju' => 'required|string|max:20',
            'kelurahan_id'   => 'required|exists:kelurahans,id', // Validasi kelurahan
            'nama_perumahan' => 'required|string|max:255',
            'alamat'         => 'required|string',
            'proposal'       => 'required|file|mimes:pdf,doc,docx|max:10240',
            'catatan'        => 'nullable|string',
        ]);

        // ====================================================================
        // === SOLUSI PERMANEN: LENGKAPI SEMUA KOLOM WAJIB UNTUK MEMBUAT KOMPLEK BARU ===
        // ====================================================================
        $komplek = \App\Models\Komplek::firstOrCreate(
            // Kunci pencarian: cari komplek berdasarkan nama DAN kelurahan agar unik
            [
                'nama_komplek' => $validated['nama_perumahan'],
                'kelurahan_id' => $validated['kelurahan_id'],
            ],
            // Data yang akan diisi JIKA komplek baru dibuat
            [
                'status_aset'  => 'Belum Diserahkan', // Berikan nilai default yang logis
            ]
        );

        $filePath = $request->file('proposal')->store('proposals', 'public');

        // Simpan proposal dengan menautkan ID dari komplek yang ditemukan/dibuat
        Proposal::create([
            'nama_pengaju'   => $validated['nama_pengaju'],
            'kontak_pengaju' => $validated['kontak_pengaju'],
            'kompleks_id'    => $komplek->id,
            'nama_perumahan' => $validated['nama_perumahan'], // Tetap simpan nama perumahan di proposal
            'alamat'         => $validated['alamat'],
            'proposal'       => $filePath,
            'catatan'        => $validated['catatan'],
            'user_id'        => Auth::id(),
            'status'         => 'Diajukan',
        ]);

        return redirect()->back()->with('success', 'Proposal Anda berhasil dikirim! Terima kasih.');
    }
}