<?php

namespace App\Filament\Widgets;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Komplek;
use App\Models\Pengaduan;
use App\Models\Proposal;
use App\Models\SiteStatistic;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    /**
     * Mengatur jumlah kolom grid menjadi 4.
     * Ini akan membuat layout menjadi 4 kartu per baris.
     */
    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $visitor = SiteStatistic::firstOrCreate([], ['visitor_count' => 0]);
        
        return [
            // --- BARIS 1 ---
            Stat::make('Total Pengunjung', $visitor->visitor_count)
                ->description('Statistik Web')
                ->color('success')
                ->icon('heroicon-o-globe-alt'),
            
            Stat::make('Total Pengguna', User::where('role', 'pengguna')->count())
                ->description('Akun Terdaftar')
                ->color('info')
                ->icon('heroicon-o-user-group'),
                
            Stat::make('Total Proposal', Proposal::count())
                ->description('Semua Masuk')
                ->color('warning')
                ->icon('heroicon-o-document-text'),
                
            Stat::make('Total Pengaduan', Pengaduan::count())
                ->description('Semua Masuk')
                ->color('danger')
                ->icon('heroicon-o-chat-bubble-left-right'),

            // --- BARIS 2 ---
            Stat::make('Total Kecamatan', Kecamatan::count())
                ->color('primary')
                ->icon('heroicon-o-map'),

            Stat::make('Total Kelurahan', Kelurahan::count())
                ->color('primary')
                ->icon('heroicon-o-map-pin'),

            Stat::make('Jumlah Komplek', Komplek::count())
                ->description('Perumahan Terdata')
                ->color('success')
                ->icon('heroicon-o-home-modern'),

            Stat::make('Aset Diserahkan', Komplek::where('status_aset', 'Sudah Diserahkan')->count())
                ->description('Sertifikat PSU')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->icon('heroicon-o-document-check'),
        ];
    }
}