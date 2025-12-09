<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
// Import Model untuk mengakses konstanta status
use App\Models\Pengaduan;
use App\Models\Proposal;
// TAMBAHAN: Model KomplekBaru
use App\Models\KomplekBaru;

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

        // ====== PENGADUAN: stats + 5 terbaru (LOGIKA TETAP) ======
        $pengaduan = [
            'stats'  => ['total' => 0, 'diterima' => 0, 'selesai' => 0],
            'recent' => collect(),
        ];

        if (Schema::hasTable('pengaduans')) {
            $baseQuery = fn() => DB::table('pengaduans')->where('user_id', $user->id);

            // Hitung statistik
            $pengaduan['stats']['total']    = $baseQuery()->count();
            $pengaduan['stats']['diterima'] = $baseQuery()->where('status', Pengaduan::STATUS_DITERIMA)->count();
            $pengaduan['stats']['selesai']  = $baseQuery()->where('status', Pengaduan::STATUS_DISETUJUI_KADIS)->count(); // Note: Di model Pengaduan biasanya STATUS_SELESAI, tapi saya ikuti kode Anda yg merujuk STATUS_DISETUJUI_KADIS jika itu yg dipakai.

            // Ambil 5 pengaduan terbaru
            $pengaduan['recent'] = DB::table('pengaduans')
                ->select([
                    'id','status','created_at', 'isi_pengaduan',
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
                ->get();
        }

        // ====== E-PROPOSAL: stats + 5 terbaru (LOGIKA TETAP) ======
        $proposalTable = 'proposals';

        $proposal = [
            'stats'  => ['diajukan'=>0,'diverifikasi'=>0,'disetujui'=>0,'ditolak'=>0,'total'=>0],
            'recent' => collect(),
        ];

        if (Schema::hasTable($proposalTable)) {
            $rows = DB::table($proposalTable)
                ->select('status', DB::raw('COUNT(*) as c'))
                ->where('user_id', $user->id)
                ->groupBy('status')
                ->pluck('c','status');

            $proposal['stats']['diajukan']     = (int) ($rows[Proposal::STATUS_DIAJUKAN] ?? 0);
            $proposal['stats']['diverifikasi'] = (int) ($rows[Proposal::STATUS_DIVERIFIKASI_JF] ?? 0);
            $proposal['stats']['disetujui']    = (int) ($rows[Proposal::STATUS_DISETUJUI_KABID] ?? 0) + (int) ($rows[Proposal::STATUS_DISETUJUI_KADIS] ?? 0);
            $proposal['stats']['ditolak']      = (int) ($rows[Proposal::STATUS_DITOLAK] ?? 0);
            $proposal['stats']['total']        = $proposal['stats']['diajukan']
                                                + $proposal['stats']['diverifikasi']
                                                + $proposal['stats']['disetujui']
                                                + $proposal['stats']['ditolak'];

            $proposal['recent'] = DB::table($proposalTable)
                ->join('kompleks', $proposalTable.'.kompleks_id', '=', 'kompleks.id')
                ->select([
                    $proposalTable.'.id', $proposalTable.'.status', $proposalTable.'.created_at',
                    'kompleks.nama_komplek AS judul'
                ])
                ->where($proposalTable.'.user_id', $user->id)
                ->orderByDesc($proposalTable.'.created_at')
                ->limit(5)
                ->get()
                ->map(function ($r) {
                    return $r;
                });
        }

        // ====== KOMPLEK BARU (LOGIKA BARU DITAMBAHKAN DISINI) ======
        // Mengambil riwayat pengajuan komplek baru oleh user
        $komplekBaru = collect();
        if (Schema::hasTable('komplek_barus')) {
            $komplekBaru = KomplekBaru::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        // ====== TIMELINE (LOGIKA DIGABUNGKAN) ======
        // Menggabungkan Pengaduan + Proposal + Komplek Baru ke satu timeline urut waktu
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
            // Menambahkan Komplek Baru ke Timeline
            ->merge($komplekBaru->map(fn($r)=>[
                'tipe'  => 'Ajuan Komplek',
                'judul' => $r->nama_komplek, // Menampilkan nama komplek yang diajukan
                'status'=> $r->status,
                'waktu' => $r->created_at,
            ]))
            ->sortByDesc('waktu')
            ->take(8)
            ->values();

        $announcements = [];

        // Mengirimkan variabel baru $komplekBaru ke view
        return view('user.dashboard', compact('akun','pengaduan','proposal','komplekBaru','timeline','announcements'));
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications;

        // Mark all unread notifications as read
        $user->unreadNotifications->markAsRead();

        return view('user.notifikasi', compact('notifications'));
    }
}