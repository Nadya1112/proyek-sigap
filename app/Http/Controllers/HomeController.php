<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Proposal;
use App\Models\Pengaduan;
use App\Models\SiteStatistic;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan data statistik.
     */
    public function index()
    {
        // Menggunakan DB::transaction untuk memastikan penambahan visitor aman
        DB::transaction(function () {
            // Ambil atau buat baris statistik, lalu increment visitor_count
            SiteStatistic::firstOrCreate([])->increment('visitor_count');
        });
        
        // Ambil semua data statistik dalam satu query jika memungkinkan
        $stats = [
            'totalPengunjung' => SiteStatistic::value('visitor_count') ?? 0,
            'totalPengguna' => User::where('role', 'pengguna')->count(),
            'totalProposal' => Proposal::count(),
            'totalPengaduan' => Pengaduan::count(),
        ];

        return view('public.home', $stats);
    }
}