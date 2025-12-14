<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Pengaduan;
use App\Models\Proposal;

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

        // ====== PENGADUAN ======
        $pengaduan = [
            'stats'  => ['total' => 0, 'diterima' => 0, 'selesai' => 0],
            'recent' => collect(),
        ];

        if (Schema::hasTable('pengaduans')) {
            $baseQuery = fn() => DB::table('pengaduans')->where('user_id', $user->id);
            
            $pengaduan['stats']['total']    = $baseQuery()->count();
            $pengaduan['stats']['diterima'] = $baseQuery()->where('status', Pengaduan::STATUS_DITERIMA)->count();
            $pengaduan['stats']['selesai']  = $baseQuery()->where('status', Pengaduan::STATUS_DISETUJUI_KADIS)->count();

            $pengaduan['recent'] = DB::table('pengaduans')
                ->select([
                    'id','status','created_at', 'isi_pengaduan',
                    DB::raw("COALESCE(NULLIF(TRIM(isi_pengaduan), ''), CONCAT('Pengaduan oleh ', COALESCE(nama_pelapor,''))) AS judul")
                ])
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        }

        // ====== E-PROPOSAL ======
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
            $proposal['stats']['total']        = array_sum($proposal['stats']); // Total sederhana

            $proposal['recent'] = DB::table($proposalTable)
                ->join('kompleks', $proposalTable.'.kompleks_id', '=', 'kompleks.id')
                ->select([
                    $proposalTable.'.id', $proposalTable.'.status', $proposalTable.'.created_at',
                    'kompleks.nama_komplek AS judul'
                ])
                ->where($proposalTable.'.user_id', $user->id)
                ->orderByDesc($proposalTable.'.created_at')
                ->limit(5)
                ->get();
        }

        // ====== TIMELINE ======
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

        $announcements = [];

        return view('user.dashboard', compact('akun','pengaduan','proposal','timeline','announcements'));
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications;
        $user->unreadNotifications->markAsRead();
        return view('user.notifikasi', compact('notifications'));
    }
}