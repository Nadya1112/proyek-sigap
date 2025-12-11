<?php

namespace App\Observers;

use App\Models\Proposal;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Cache;

class ProposalObserver
{
    /**
     * Cache key prefix for tracking notifications.
     *
     * @var string
     */
    private const CACHE_PREFIX = 'notification_sent_proposal_';

    /**
     * Handle the Proposal "created" event.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return void
     */
    public function created(Proposal $proposal): void
    {
        $cacheKey = self::CACHE_PREFIX . 'created_' . $proposal->id;
        if (Cache::has($cacheKey)) {
            return;
        }

        // 1. Pengguna mengajukan -> Notifikasi ke Staff & Super Admin
        $staffUsers = User::where('role', User::ROLE_STAFF)->get();
        $superAdmins = User::where('role', User::ROLE_ADMIN)->get();
        $recipients = $staffUsers->merge($superAdmins);

        if ($recipients->isNotEmpty()) {
            Notification::make()
                ->title('E-Proposal Baru Masuk')
                ->body("Proposal dari {$proposal->nama_pengaju} menunggu verifikasi awal.")
                ->icon('heroicon-o-inbox-arrow-down')
                ->iconColor('primary')
                ->actions([
                    Action::make('view')
                        ->label('Tinjau')
                        ->url(fn() => "/admin/proposals/{$proposal->id}/edit")
                        ->button(),
                ])
                ->sendToDatabase($recipients);
        }

        // 2. Notifikasi ke pengguna bahwa proposal telah diterima
        if ($proposal->user) {
            Notification::make()
                ->title('Proposal Berhasil Diajukan')
                ->body('Terima kasih, proposal Anda telah kami terima dan akan segera ditinjau oleh tim kami.')
                ->icon('heroicon-o-document-check')
                ->iconColor('success')
                ->sendToDatabase($proposal->user);
        }

        Cache::put($cacheKey, true, now()->addMinutes(1));
    }

    /**
     * Handle the Proposal "updated" event.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return void
     */
    public function updated(Proposal $proposal): void
    {
        if ($proposal->isDirty('status')) {
            $cacheKey = self::CACHE_PREFIX . 'updated_' . $proposal->id . '_' . $proposal->status;
            if (Cache::has($cacheKey)) {
                return;
            }
            
            $newStatus = $proposal->status;
            
            $targetAdmins = collect();
            $title = '';
            $body = '';
            $url = "/admin/proposals/{$proposal->id}/edit";
            $superAdmins = User::where('role', User::ROLE_ADMIN)->get();

            switch ($newStatus) {
                case Proposal::STATUS_DITERIMA:
                    $targetAdmins = User::where('role', User::ROLE_JF_PSU)->get();
                    $title = 'Proposal Perlu Verifikasi';
                    $body = "Proposal {$proposal->nama_pengaju} telah diterima Staff.";
                    break;
                case Proposal::STATUS_DIVERIFIKASI_JF:
                    $targetAdmins = User::where('role', User::ROLE_KABID)->get();
                    $title = 'Menunggu Persetujuan Kabid';
                    $body = "Proposal {$proposal->nama_pengaju} telah diverifikasi JF PSU.";
                    break;
                case Proposal::STATUS_DISETUJUI_KABID:
                    $targetAdmins = User::where('role', User::ROLE_KADIS)->get();
                    $title = 'Menunggu Persetujuan Akhir';
                    $body = "Proposal {$proposal->nama_pengaju} menunggu persetujuan Kepala Dinas.";
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

            if ($proposal->user) {
                $userTitle = 'Status Proposal Diperbarui';
                $userBody = "Status proposal Anda sekarang: {$newStatus}.";
                $icon = 'heroicon-o-information-circle';
                $color = 'info';

                if ($newStatus === Proposal::STATUS_DISETUJUI_KADIS) {
                    $userTitle = 'Proposal Disetujui';
                    $userBody = 'Selamat! Proposal Anda telah disetujui sepenuhnya oleh Kepala Dinas.';
                    $icon = 'heroicon-o-check-circle';
                    $color = 'success';
                } elseif ($newStatus === Proposal::STATUS_DITOLAK) {
                    $userTitle = 'Proposal Ditolak';
                    $userBody = 'Mohon maaf, proposal Anda belum dapat kami setujui. Silakan cek catatan.';
                    $icon = 'heroicon-o-x-circle';
                    $color = 'danger';
                }

                Notification::make()
                    ->title($userTitle)
                    ->body($userBody)
                    ->icon($icon)
                    ->iconColor($color)
                    ->sendToDatabase($proposal->user);
            }

            Cache::put($cacheKey, true, now()->addMinutes(1));
        }
    }
}