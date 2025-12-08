<?php
namespace App\Filament\Widgets;
use App\Models\KomplekBaru;
use App\Models\Pengaduan;
use App\Models\Proposal;
use App\Models\SiteStatistic;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
class StatsOverview extends BaseWidget
{
    protected function getStats(): array {
        $visitor = SiteStatistic::firstOrCreate([], ['visitor_count' => 0]);
        $pendingKomplek = KomplekBaru::where('status', 'Pending')->count();
        return [
            Stat::make('Total Pengunjung', $visitor->visitor_count)
            ->color('success')
            ->icon('heroicon-o-user-group'),
            Stat::make('Total Pengguna', User::where('role', 'pengguna')
            ->count())
            ->color('info')
            ->icon('heroicon-o-user'),
            Stat::make('Total Proposal', Proposal::count())
            ->color('warning')
            ->icon('heroicon-o-document-text'),
            Stat::make('Total Pengaduan', Pengaduan::count())
            ->color('danger')
            ->icon('heroicon-o-chat-bubble-left-right'),
            Stat::make('Pengajuan Komplek Baru', $pendingKomplek)
                ->description($pendingKomplek > 0 ? 'Perlu Ditinjau' : 'Semua Aman')
                ->descriptionIcon($pendingKomplek > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-circle')
                ->color($pendingKomplek > 0 ? 'warning' : 'success') // Warna kuning jika ada pending, hijau jika kosong
                ->icon('heroicon-o-building-office-2'),
        ];
    }
}