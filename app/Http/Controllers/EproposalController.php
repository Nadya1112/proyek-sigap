<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Komplek;
use App\Models\Proposal;
use App\Models\User;
use App\Notifications\ProposalUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EproposalController extends Controller
{
    /**
     * Menampilkan form, mengirim data Kecamatan awal.
     */
    public function showForm()
    {
        $stats = [
            'total'         => Proposal::count(),
            'diajukan'       => Proposal::where('status', Proposal::STATUS_DIAJUKAN)->count(),
            'diverifikasi'  => Proposal::where('status', Proposal::STATUS_DIVERIFIKASI_JF)->count(),
            'disetujui'     => Proposal::whereIn('status', [Proposal::STATUS_DISETUJUI_KABID, Proposal::STATUS_DISETUJUI_KADIS])->count(),
        ];
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get(['id', 'nama_kecamatan']);
        return view('public.eproposal', compact('stats', 'kecamatans'));
    }

    /**
     * Menyimpan proposal (Standard E-Proposal Logic).
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengajukan proposal.');
        }
        
        $validated = $request->validate([
            'nama_pengaju'   => 'required|string|max:255',
            'kontak_pengaju' => 'required|string|max:20',
            'kompleks_id'    => 'required|exists:kompleks,id',
            'alamat'         => 'required|string',
            'proposal'       => 'required|file|mimes:pdf,doc,docx|max:10240',
            'catatan'        => 'nullable|string',
        ]);

        $filePath = $request->file('proposal')->store('proposals', 'public');
        
        $proposal = Proposal::create([
            'user_id'        => Auth::id(),
            'kompleks_id'    => $validated['kompleks_id'],
            'nama_pengaju'   => $validated['nama_pengaju'],
            'kontak_pengaju' => $validated['kontak_pengaju'],
            'alamat'         => $validated['alamat'],
            'proposal'       => $filePath,
            'catatan'        => $validated['catatan'],
            'status'         => 'Diajukan',
        ]);

        // Kirim notifikasi ke semua admin yang relevan
        $admins = User::whereIn('role', [
            User::ROLE_STAFF,
            User::ROLE_JF_PSU,
            User::ROLE_KABID,
            User::ROLE_KADIS
        ])->get();

        $title = 'Proposal Baru Diterima';
        $body = "Proposal baru telah diajukan oleh {$proposal->nama_pengaju} dan menunggu tinjauan.";

        foreach ($admins as $admin) {
            $admin->notify(new ProposalUpdatedNotification($proposal, $title, $body));
        }

        return redirect()->route('eproposal')->with('success', 'Proposal Anda berhasil dikirim! Terima kasih.');
    }

    /**
     * Mengambil Kelurahan berdasarkan Kecamatan ID (AJAX).
     */
    public function getKelurahan($kecamatanId)
    {
        if (!ctype_digit((string)$kecamatanId)) {
            return response()->json([], 400);
        }

        $kelurahans = DB::table('kelurahans')
            ->where('kecamatan_id', $kecamatanId)
            ->orderBy('nama_kelurahan')
            ->select('id', 'nama_kelurahan')
            ->get();

        return response()->json($kelurahans);
    }

    /**
     * Mengambil Komplek berdasarkan Kelurahan ID (AJAX).
     */
    public function getKompleksByKelurahan($kelurahanId)
    {
        if (!ctype_digit((string)$kelurahanId)) {
            return response()->json([], 400);
        }

        $kompleks = DB::table('kompleks')
            ->where('kelurahan_id', $kelurahanId)
            ->orderBy('nama_komplek')
            ->select('id', 'nama_komplek')
            ->get();

        return response()->json($kompleks);
    }

    /**
     * Download Template Proposal.
     */
    public function downloadTemplate()
    {
        $filePath = 'templates/template-proposal.docx';
        $downloadName = 'Template-Proposal-PSU-SIGAP.docx';

        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $downloadName);
        } else {
            abort(404, 'File template tidak ditemukan.');
        }
    }
}