<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal; // Pastikan model Proposal ada dan benar
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

        return view('public.eproposal', $stats);
    }

    /**
     * Menyimpan data proposal baru dari form.
     */
    public function store(Request $request)
    {
        // Pastikan hanya pengguna yang sudah login yang bisa mengirim
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengajukan proposal.');
        }

        $validated = $request->validate([
            'nama_pengaju'   => 'required|string|max:255',
            'kontak_pengaju' => 'required|string|max:20',
            'nama_perumahan' => 'required|string|max:255',
            'alamat'         => 'required|string',
            'proposal'       => 'required|file|mimes:pdf,doc,docx|max:10240', // Maks 10MB
            'catatan'        => 'nullable|string',
        ]);

        $filePath = $request->file('proposal')->store('proposals', 'public');

        Proposal::create([
            'nama_pengaju'   => $validated['nama_pengaju'],
            'kontak_pengaju' => $validated['kontak'],
            'nama_perumahan' => $validated['nama_perumahan'],
            'alamat'         => $validated['alamat'],
            'proposal'      => $proposal,
            'catatan'        => $validated['catatan'],
            'user_id'        => Auth::id(), // Tautkan dengan user yang login
            'status'         => 'terkirim', // Status awal
        ]);

        return redirect()->back()->with('success', 'Proposal Anda berhasil dikirim! Terima kasih.');
    }
}