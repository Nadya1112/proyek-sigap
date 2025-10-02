<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Informasi akun
        $akun = [
            'name'     => $user->name ?? $user->email,
            'email'    => $user->email,
            'verified' => !is_null($user->email_verified_at),
            'joined'   => $user->created_at,
        ];

        // ====== PENGADUAN: stats + 5 terbaru (mengikuti struktur tabel) ======
$pengaduan = [
    'stats'  => ['diterima'=>0,'diproses'=>0,'selesai'=>0,'total'=>0],
    'recent' => collect(),
];

if (Schema::hasTable('pengaduans')) {
    // hitung per status (DB pakai kapital di enum)
    $rows = DB::table('pengaduans')
        ->select('status', DB::raw('COUNT(*) as c'))
        ->where('user_id', $user->id)
        ->groupBy('status')
        ->pluck('c','status');

    $pengaduan['stats']['diterima'] = (int) ($rows['Diterima']  ?? 0);
    $pengaduan['stats']['diproses'] = (int) ($rows['Diproses'] ?? 0);
    $pengaduan['stats']['selesai']  = (int) ($rows['Selesai']  ?? 0);
    $pengaduan['stats']['total']    = $pengaduan['stats']['diterima']
                                    + $pengaduan['stats']['diproses']
                                    + $pengaduan['stats']['selesai'];

    // ambil 5 terbaru – tampilkan ringkasan isi_pengaduan sebagai "judul"
    $pengaduan['recent'] = DB::table('pengaduans')
        ->select([
            'id','status','created_at',
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
            // normalisasi status untuk UI (diproses => proses)
            $s = strtolower($r->status);            // diterima/diproses/selesai
            $r->status = $s === 'diproses' ? 'proses' : $s;
            return $r;
        });
}

// ====== E-PROPOSAL: stats + 5 terbaru (mengikuti struktur tabel) ======
$proposalTable = Schema::hasTable('proposals') ? 'proposals'
                : (Schema::hasTable('eproposals') ? 'eproposals' : null);

$proposal = [
    'stats'  => ['diajukan'=>0,'diverifikasi'=>0,'disetujui'=>0,'ditolak'=>0,'total'=>0],
    'recent' => collect(),
];

if ($proposalTable) {
    // hitung per status (DB pakai kapital di enum)
    $rows = DB::table($proposalTable)
        ->select('status', DB::raw('COUNT(*) as c'))
        ->where('user_id', $user->id)
        ->groupBy('status')
        ->pluck('c','status');

    $proposal['stats']['diajukan']     = (int) ($rows['Diajukan']     ?? 0);
    $proposal['stats']['diverifikasi'] = (int) ($rows['Diverifikasi'] ?? 0);
    $proposal['stats']['disetujui']    = (int) ($rows['Disetujui']    ?? 0);
    $proposal['stats']['ditolak']      = (int) ($rows['Ditolak']      ?? 0);
    $proposal['stats']['total']        = $proposal['stats']['diajukan']
                                       + $proposal['stats']['diverifikasi']
                                       + $proposal['stats']['disetujui']
                                       + $proposal['stats']['ditolak'];

    // ambil 5 terbaru – judul dari nama file dokumen_proposal; fallback potong catatan
    $proposal['recent'] = DB::table($proposalTable)
        ->select([
            'id','status','created_at',
            DB::raw("
                COALESCE(
                    NULLIF(TRIM(proposal), ''),
                    LEFT(COALESCE(catatan,''), 80)
                ) AS judul
            ")
        ])
        ->where('user_id', $user->id)
        ->orderByDesc('created_at')
        ->limit(5)
        ->get()
        ->map(function ($r) {
            // normalisasi ke huruf kecil (diajukan/diverifikasi/disetujui/ditolak)
            $r->status = strtolower($r->status);
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
