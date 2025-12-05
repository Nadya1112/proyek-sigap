<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

class PublicFormController extends Controller
{
    // =======================================================
    // === LOGIKA UNTUK HALAMAN PENGADUAN ===
    // =======================================================

    /**
     * Menampilkan form pengaduan beserta statistik.
     */
    public function showPengaduanForm()// Di dalam file app/Http/Controllers/PublicController.php
    {
        $stats = [
            'total'    => Pengaduan::count(),
            'diterima' => Pengaduan::where('status', 'Diterima')->count(),
            'proses'   => Pengaduan::where('status', 'Diproses')->count(),
            'selesai'  => Pengaduan::where('status', 'Selesai')->count(),
        ];

        return view('public.pengaduan', $stats);
    }

    /**
     * Menyimpan data pengaduan baru dari form.
     */
    public function storePengaduan(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk membuat pengaduan.');
        }

        $validated = $request->validate([
            'nama_pelapor'   => 'required|string|max:255',
            'kontak_pelapor' => 'required|string|max:20',
            'isi_pengaduan'  => 'required|string',
            'bukti_foto'     => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $filePath = $request->file('bukti_foto')->store('pengaduan', 'public');

        Pengaduan::create([
            'nama_pelapor'   => $validated['nama_pelapor'],
            'kontak_pelapor' => $validated['kontak_pelapor'],
            'isi_pengaduan'  => $validated['isi_pengaduan'],
            'bukti_foto'     => $filePath,
            'user_id'        => Auth::id(),
            'status'         => 'Diterima',
        ]);

        return redirect()->back()->with('success', 'Pengaduan Anda berhasil dikirim! Terima kasih.');
    }


    // =======================================================
    // === LOGIKA UNTUK HALAMAN E-PROPOSAL ===
    // =======================================================

    /**
     * Menampilkan form E-Proposal beserta statistik.
     */
    public function showEproposalForm()
    {
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
    public function storeEproposal(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengajukan proposal.');
        }

        $validated = $request->validate([
            'nama_pengaju'   => 'required|string|max:255',
            'kontak'         => 'required|string|max:20',
            'nama_perumahan' => 'required|string|max:255',
            'alamat'         => 'required|string',
            'proposal'       => 'required|file|mimes:pdf,doc,docx|max:10240',
            'catatan'        => 'nullable|string',
        ]);

        $filePath = $request->file('proposal')->store('proposals', 'public');

        Proposal::create([
            'nama_pengaju'   => $validated['nama_pengaju'],
            'kontak_pengaju' => $validated['kontak'],
            'nama_perumahan' => $validated['nama_perumahan'],
            'alamat'         => $validated['alamat'],
            'proposal'       => $filePath,
            'catatan'        => $validated['catatan'],
            'user_id'        => Auth::id(),
            'status'         => 'terkirim',
        ]);

        return redirect()->back()->with('success', 'Proposal Anda berhasil dikirim! Terima kasih.');
    }
}