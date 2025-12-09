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
     * Menyimpan proposal (Logika ini sudah benar dan tidak berubah).
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengajukan proposal.');
        }
        $validated = $request->validate([
            'nama_pengaju'   => 'required|string|max:255',
            'kontak_pengaju' => 'required|string|max:20',
            'kompleks_id'    => 'required|exists:kompleks,id', // Validasi kunci
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
     * Mengambil Kelurahan berdasarkan Kecamatan ID (Menggunakan Query Builder).
     */
    public function getKelurahan($kecamatanId) // Terima ID langsung
    {
        Log::info("Mencari kelurahan untuk kecamatan ID: " . $kecamatanId);

        // Validasi sederhana
        if (!ctype_digit((string)$kecamatanId)) {
            Log::warning("ID Kecamatan tidak valid: " . $kecamatanId);
            return response()->json([], 400); // Bad request
        }

        // Query langsung ke tabel kelurahans
        $kelurahans = DB::table('kelurahans')
            ->where('kecamatan_id', $kecamatanId)
            ->orderBy('nama_kelurahan')
            ->select('id', 'nama_kelurahan') // Eksplisit pilih kolom
            ->get();

        Log::info("Kelurahan ditemukan: " . $kelurahans->count());
        return response()->json($kelurahans);
    }

    /**
     * Mengambil Komplek berdasarkan Kelurahan ID (Menggunakan Query Builder).
     */
    public function getKompleksByKelurahan($kelurahanId) // Terima ID langsung
    {
        Log::info("Mencari komplek untuk kelurahan ID: " . $kelurahanId);

        if (!ctype_digit((string)$kelurahanId)) {
            Log::warning("ID Kelurahan tidak valid: " . $kelurahanId);
            return response()->json([], 400);
        }

        // Query langsung ke tabel kompleks
        $kompleks = DB::table('kompleks')
            ->where('kelurahan_id', $kelurahanId)
            ->orderBy('nama_komplek')
            ->select('id', 'nama_komplek') // Eksplisit pilih kolom
            ->get();

        Log::info("Komplek ditemukan: " . $kompleks->count());
        return response()->json($kompleks);
    }

    public function downloadTemplate()
    {
        // Path relatif terhadap disk 'public' (storage/app/public)
        $filePath = 'templates/template-proposal.docx';
        $downloadName = 'Template-Proposal-PSU-SIGAP.docx'; // Nama file saat diunduh

        // Cek apakah file ada di disk 'public'
        if (Storage::disk('public')->exists($filePath)) {
            // Jika ada, kembalikan sebagai respons download
            return Storage::disk('public')->download($filePath, $downloadName);
        } else {
            // Jika file tidak ditemukan, tampilkan error 404
            Log::error("File template tidak ditemukan di: " . $filePath);
            abort(404, 'File template tidak ditemukan.');
        }
    }

    public function storeKomplekBaru(Request $request)
    {
        // 1. Validasi
        $validated = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'nama_komplek' => 'required|string|max:255',
            'alamat'       => 'required|string|max:500',
            'nomor_hp'     => 'required|string|max:20',
        ]);

        // 2. Simpan ke tabel arsip (komplek_barus)
        try {
            \App\Models\KomplekBaru::create([
                'user_id'      => Auth::id() ?? null, // Bisa null jika user belum login, tapi idealnya login
                'kecamatan_id' => $validated['kecamatan_id'],
                'kelurahan_id' => $validated['kelurahan_id'],
                'nama_komplek' => $validated['nama_komplek'],
                'alamat'       => $validated['alamat'],
                'nomor_hp'     => $validated['nomor_hp'],
                'status'       => 'Pending',
            ]);

            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil dikirim.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data.'], 500);
        }
    }

}