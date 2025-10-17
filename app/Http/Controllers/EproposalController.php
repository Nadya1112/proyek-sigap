<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Komplek;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
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

        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();

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
            'kompleks_id'    => 'required|exists:kompleks,id', // Key validation
            'alamat'         => 'required|string',
            'proposal'       => 'required|file|mimes:pdf,doc,docx|max:10240',
            'catatan'        => 'nullable|string',
        ]);

        $filePath = $request->file('proposal')->store('proposals', 'public');

        Proposal::create([
            'user_id'        => Auth::id(),
            'kompleks_id'    => $validated['kompleks_id'], // Correctly stores the ID
            'nama_pengaju'   => $validated['nama_pengaju'],
            'kontak_pengaju' => $validated['kontak_pengaju'],
            'alamat'         => $validated['alamat'],
            'proposal'       => $filePath,
            'catatan'        => $validated['catatan'],
            'status'         => 'Diajukan',
        ]);

        return redirect()->route('eproposal')->with('success', 'Proposal Anda berhasil dikirim! Terima kasih.');
    }

    public function getKelurahan(Kecamatan $kecamatan)
    {
        // Mengambil kelurahan yang berelasi dengan kecamatan yang dipilih
        return response()->json($kecamatan->kelurahans()->orderBy('nama_kelurahan')->get());
    }

    /**
     * NEW FUNCTION: To verify the existence of a housing complex name.
     */
    public function verifyKompleks(Request $request)
    {
        $validated = $request->validate([
            'q' => 'required|string',
            'kelurahan_id' => 'required|exists:kelurahans,id',
        ]);

        $query = Str::squish($validated['q']);

        // Cari komplek di dalam kelurahan yang dipilih, menggunakan LIKE untuk fleksibilitas
        $kompleks = Komplek::where('kelurahan_id', $validated['kelurahan_id'])
            ->where('nama_komplek', 'LIKE', "%{$query}%") // <-- Menggunakan LIKE
            ->with('kelurahan.kecamatan')
            ->first();

        if ($kompleks) {
            return response()->json(['status' => 'found', 'data' => $kompleks]);
        } else {
            return response()->json(['status' => 'notFound']);
        }
    }
}