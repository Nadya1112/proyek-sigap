<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    /**
     * Menampilkan form pengaduan beserta data statistik yang benar.
     */
    public function showPengaduanForm()
{
    $stats = [
        'total'    => Pengaduan::count(),
        'diterima' => Pengaduan::whereIn('status', [Pengaduan::STATUS_DITERIMA, Pengaduan::STATUS_DIVERIFIKASI_JF])->count(), // Gabung Diterima & Diverifikasi
        'proses'   => Pengaduan::where('status', Pengaduan::STATUS_DIPROSES)->count(),
        'selesai'  => Pengaduan::where('status', Pengaduan::STATUS_SELESAI)->count(),
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
}