<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
// Import Model untuk mengakses konstanta status
use App\Models\Pengaduan;
use App\Models\Proposal;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Informasi akun (Tidak berubah)
        $akun = [
            'name'     => $user->name ?? $user->email,
            'email'    => $user->email,
            'verified' => !is_null($user->email_verified_at),
            'joined'   => $user->created_at,
        ];

        // ====== PENGADUAN: stats + 5 terbaru (DIPERBARUI) ======
        $pengaduan = [
            'stats'  => ['diterima'=>0,'diproses'=>0,'selesai'=>0,'total'=>0],
            'recent' => collect(),
        ];

        if (Schema::hasTable('pengaduans')) {
            // PERBAIKAN (Tahap D): Query statistik menggunakan konstanta status baru
            $rows = DB::table('pengaduans')
                ->select('status', DB::raw('COUNT(*) as c'))
                ->where('user_id', $user->id)
                ->groupBy('status')
                ->pluck('c','status');

            // Sesuaikan perhitungan statistik berdasarkan status baru
            $pengaduan['stats']['diterima'] = (int) ($rows[Pengaduan::STATUS_DITERIMA] ?? 0) + (int) ($rows[Pengaduan::STATUS_DIVERIFIKASI_JF] ?? 0);
            $pengaduan['stats']['diproses'] = (int) ($rows[Pengaduan::STATUS_DIPROSES] ?? 0);
            $pengaduan['stats']['selesai']  = (int) ($rows[Pengaduan::STATUS_SELESAI] ?? 0);
            $pengaduan['stats']['total']    = $pengaduan['stats']['diterima']
                                             + $pengaduan['stats']['diproses']
                                             + $pengaduan['stats']['selesai']
                                             + (int) ($rows[Pengaduan::STATUS_DITOLAK] ?? 0); // Total termasuk yg ditolak

            // PERBAIKAN (Error): Tambahkan 'isi_pengaduan' ke select
            $pengaduan['recent'] = DB::table('pengaduans')
                ->select([
                    'id','status','created_at', 'isi_pengaduan', // <-- TAMBAHKAN INI
                    DB::raw("
                        COALESCE(
                            NULLIF(TRIM(isi_pengaduan), ''),
                            CONCAT('Pengaduan oleh ', COALESCE(nama_pelapor,''))
                        ) AS judul
                    ")
                ])
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get()
                ->map(function ($r) {
                    // PERBAIKAN (Tahap D): Normalisasi status tidak lagi diperlukan
                    // $s = strtolower($r->status);
                    // $r->status = $s === 'diproses' ? 'proses' : $s;
                    return $r;
                });
        }

        // ====== E-PROPOSAL: stats + 5 terbaru (DIPERBARUI) ======
        $proposalTable = 'proposals'; // Asumsi tabel proposals

        $proposal = [
            'stats'  => ['diajukan'=>0,'diverifikasi'=>0,'disetujui'=>0,'ditolak'=>0,'total'=>0],
            'recent' => collect(),
        ];

        if (Schema::hasTable($proposalTable)) {
            // PERBAIKAN (Tahap D): Query statistik menggunakan konstanta status baru
            $rows = DB::table($proposalTable)
                ->select('status', DB::raw('COUNT(*) as c'))
                ->where('user_id', $user->id)
                ->groupBy('status')
                ->pluck('c','status');

            $proposal['stats']['diajukan']     = (int) ($rows[Proposal::STATUS_DIAJUKAN] ?? 0);
            $proposal['stats']['diverifikasi'] = (int) ($rows[Proposal::STATUS_DIVERIFIKASI_JF] ?? 0);
            // Gabungkan kedua status disetujui untuk tampilan publik
            $proposal['stats']['disetujui']    = (int) ($rows[Proposal::STATUS_DISETUJUI_KABID] ?? 0) + (int) ($rows[Proposal::STATUS_DISETUJUI_KADIS] ?? 0);
            $proposal['stats']['ditolak']      = (int) ($rows[Proposal::STATUS_DITOLAK] ?? 0);
            $proposal['stats']['total']        = $proposal['stats']['diajukan']
                                                + $proposal['stats']['diverifikasi']
                                                + $proposal['stats']['disetujui']
                                                + $proposal['stats']['ditolak'];

            // PERBAIKAN (Error): Ambil 'proposal' (nama file) dan relasi ke komplek
            // Kita perlu join untuk mengambil nama komplek sebagai judul
            $proposal['recent'] = DB::table($proposalTable)
                ->join('kompleks', $proposalTable.'.kompleks_id', '=', 'kompleks.id')
                ->select([
                    $proposalTable.'.id', $proposalTable.'.status', $proposalTable.'.created_at',
                    'kompleks.nama_komplek AS judul' // Ambil nama komplek sebagai judul
                ])
                ->where($proposalTable.'.user_id', $user->id)
                ->orderByDesc($proposalTable.'.created_at')
                ->limit(5)
                ->get()
                ->map(function ($r) {
                    // PERBAIKAN (Tahap D): Normalisasi status tidak lagi diperlukan
                    // $r->status = strtolower($r->status);
                    return $r;
                });
        }


        // Timeline (gabungan pengaduan + proposal, max 8)
        $timeline = collect($pengaduan['recent'])->map(fn($r)=>[
                'tipe'  => 'Pengaduan',
                'judul' => $r->judul,
                'status'=> $r->status,
                'waktu' => $r->created_at,
            ])
            ->merge(collect($proposal['recent'])->map(fn($r)=>[
                'tipe'  => 'E-Proposal',
                'judul' => $r->judul,
                'status'=> $r->status,
                'waktu' => $r->created_at,
            ]))
            ->sortByDesc('waktu')
            ->take(8)
            ->values();

        // (opsional) pengumuman dari DB; jika tabel tak ada, kirim array kosong
        $announcements = [];

        return view('user.dashboard', compact('akun','pengaduan','proposal','timeline','announcements'));
    }
}