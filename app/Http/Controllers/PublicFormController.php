<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Proposal;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

class PublicFormController extends Controller
{
    public function proposal(Request $r)
    {
        $r->validate([
            'nama_pengaju'=>'required|string|max:150',
            'kontak'=>'required|string|max:30',
            'nama_perumahan'=>'nullable|string|max:150',
            'alamat'=>'nullable|string|max:255',
            'proposal'=>'required|file|mimes:pdf,doc,docx|max:10240',
            'catatan'=>'nullable|string'
        ]);

        $file = $r->file('proposal');
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('proposals', $filename, 'public');

        $proposal = Proposal::create([
            'nama_pengaju'=>$r->nama_pengaju,
            'kontak'=>$r->kontak,
            'nama_perumahan'=>$r->nama_perumahan,
            'alamat'=>$r->alamat,
            'file_path'=>$path,
            'catatan'=>$r->catatan,
            'status'=>'terkirim'
        ]);

        return back()->with('success','Proposal berhasil dikirim. ID: '.$proposal->id);
    }

    public function pengaduan(Request $request)
    {
        // Pastikan hanya user yang sudah login yang bisa mengirim
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk membuat pengaduan.');
        }

        // Validasi input
        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'kontak_pelapor' => 'required|string|max:20',
            'isi_pengaduan' => 'required|string',
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $filePath = null;
        // PERBAIKAN: Menggunakan metode store yang lebih eksplisit
        if ($request->hasFile('bukti_foto')) {
            // Simpan di dalam 'storage/app/public/pengaduan'
            // dan path yang dikembalikan adalah 'pengaduan/namafile.jpg'
            $filePath = $request->file('bukti_foto')->store('pengaduan', 'public');
        }

        // Tambahkan user_id dari user yang sedang login
        $validated['user_id'] = Auth::id();
        $validated['bukti_foto'] = $filePath; // Masukkan path yang benar ke array

        // Simpan ke database
        Pengaduan::create($validated);

        return redirect()->back()->with('success', 'Pengaduan Anda berhasil dikirim! Terima kasih.');
    }

    public function showPengaduanForm()
    {
        // PERBAIKAN: Menghitung statistik pengaduan dari database
        $stats = [
            'total'   => Pengaduan::count(),
            'selesai' => Pengaduan::where('status', 'Selesai')->count(),
            'proses'  => Pengaduan::where('status', 'Diproses')->count(),
            // Status awal saat pengaduan dibuat adalah 'Diterima'
            'belum'   => Pengaduan::where('status', 'Diterima')->count(), 
        ];

        // Kirim data statistik ke view
        return view('public.pengaduan', $stats);
    }
}
