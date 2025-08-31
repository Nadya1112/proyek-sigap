<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Proposal;
use App\Models\Pengaduan;

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

    public function pengaduan(Request $r)
    {
        $r->validate([
            'nama'=>'nullable|string|max:150',
            'kontak'=>'nullable|string|max:30',
            'isi'=>'required|string',
            'bukti'=>'nullable|image|max:5120'
        ]);

        $path = null;
        if ($r->hasFile('bukti')) {
            $file = $r->file('bukti');
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('pengaduan', $filename, 'public');
        }

        $pengaduan = Pengaduan::create([
            'nama'=>$r->nama,
            'kontak'=>$r->kontak,
            'isi'=>$r->isi,
            'bukti_path'=>$path,
            'status'=>'terkirim'
        ]);

        return back()->with('success','Pengaduan berhasil dikirim. ID: '.$pengaduan->id);
    }
}
