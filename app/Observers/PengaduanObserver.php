<?php

namespace App\Observers;

use App\Models\Pengaduan;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Cache;

class PengaduanObserver
{
    /**
     * Cache key prefix for tracking notifications.
     *
     * @var string
     */
    private const CACHE_PREFIX = 'notification_sent_pengaduan_';

    /**
     * Handle the Pengaduan "created" event.
     *
     * @param  \App\Models\Pengaduan  $pengaduan
     * @return void
     */
    public function created(Pengaduan $pengaduan): void
    {
        $cacheKey = self::CACHE_PREFIX . 'created_' . $pengaduan->id;
        if (Cache::has($cacheKey)) {
            return;
        }

        // 1. Pengguna mengajukan -> Notifikasi ke Staff & Super Admin
        $staffUsers = User::where('role', User::ROLE_STAFF)->get();
        $superAdmins = User::where('role', User::ROLE_ADMIN)->get();
        $recipients = $staffUsers->merge($superAdmins);
        
        if ($recipients->isNotEmpty()) {
            Notification::make()
                ->title('Pengaduan Baru Masuk')
                ->body("Pengaduan dari {$pengaduan->nama_pelapor} menunggu untuk tindak lanjut.")
                ->icon('heroicon-o-inbox-arrow-down')
                ->iconColor('primary')
                ->actions([
                    Action::make('view')
                        ->label('Lihat')
                        ->url(fn() => "/admin/pengaduans/{$pengaduan->id}/edit")
                        ->button(),
                ])
                ->sendToDatabase($recipients);
        }

        // 2. Notifikasi ke pengguna bahwa pengaduan telah diterima
        if ($pengaduan->user) {
            Notification::make()
                ->title('Pengaduan Berhasil Diajukan')
                ->body('Terima kasih, pengaduan Anda telah kami terima dan akan segera ditinjau.')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->iconColor('success')
                ->sendToDatabase($pengaduan->user);
        }

        Cache::put($cacheKey, true, now()->addMinutes(1));
    }

    /**
     * Handle the Pengaduan "updated" event.
     *
     * @param  \App\Models\Pengaduan  $pengaduan
     * @return void
     */
    public function updated(Pengaduan $pengaduan): void
    {
        if ($pengaduan->isDirty('status')) {
            $cacheKey = self::CACHE_PREFIX . 'updated_' . $pengaduan->id . '_' . $pengaduan->status;
            if (Cache::has($cacheKey)) {
                return;
            }

            $newStatus = $pengaduan->status;
            
            $targetAdmins = collect();
            $title = '';
            $body = '';
            $url = "/admin/pengaduans/{$pengaduan->id}/edit";
            $superAdmins = User::where('role', User::ROLE_ADMIN)->get();

            switch ($newStatus) {
                case Pengaduan::STATUS_DITERIMA:
                    $targetAdmins = User::where('role', User::ROLE_JF_PSU)->get();
                    $title = 'Pengaduan Perlu Verifikasi';
                    $body = "Pengaduan {$pengaduan->nama_pelapor} telah diterima Staff.";
                    break;
                case Pengaduan::STATUS_DIVERIFIKASI_JF:
                    $targetAdmins = User::where('role', User::ROLE_KABID)->get();
                    $title = 'Menunggu Persetujuan Kabid';
                    $body = "Pengaduan {$pengaduan->nama_pelapor} telah diverifikasi JF PSU.";
                    break;
                case Pengaduan::STATUS_DISETUJUI_KABID:
                    $targetAdmins = User::where('role', User::ROLE_KADIS)->get();
                    $title = 'Menunggu Persetujuan Akhir';
                    $body = "Pengaduan {$pengaduan->nama_pelapor} menunggu persetujuan Kepala Dinas.";
                    break;
            }

            $recipients = $targetAdmins->merge($superAdmins);
            
            if ($recipients->isNotEmpty()) {
                Notification::make()
                    ->title($title)
                    ->body($body)
                    ->icon('heroicon-o-bell')
                    ->warning()
                    ->actions([
                        Action::make('view')
                            ->label('Tinjau')
                            ->url($url)
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($recipients);
            }

            if ($pengaduan->user) {
                $userTitle = 'Status Pengaduan Diperbarui';
                $userBody = "Status pengaduan Anda untuk '{$pengaduan->judul_pengaduan}' sekarang: {$newStatus}.";
                $icon = 'heroicon-o-information-circle';
                $color = 'info';

                if ($newStatus === Pengaduan::STATUS_DISETUJUI_KADIS) {
                    $userTitle = 'Pengaduan Anda Telah Selesai';
                    $userBody = 'Pengaduan Anda telah ditindaklanjuti dan disetujui oleh Kepala Dinas.';
                    $icon = 'heroicon-o-check-circle';
                    $color = 'success';
                } elseif ($newStatus === Pengaduan::STATUS_DITOLAK) {
                    $userTitle = 'Pengaduan Ditolak';
                    $userBody = 'Mohon maaf, pengaduan Anda belum dapat kami proses saat ini.';
                    $icon = 'heroicon-o-x-circle';
                    $color = 'danger';
                }

                Notification::make()
                    ->title($userTitle)
                    ->body($userBody)
                    ->icon($icon)
                    ->iconColor($color)
                    ->sendToDatabase($pengaduan->user);
            }
            
            Cache::put($cacheKey, true, now()->addMinutes(1));
        }
    }
}