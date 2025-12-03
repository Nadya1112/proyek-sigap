<?php

namespace App\Observers;

use App\Models\Proposal;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class ProposalObserver
{
    /**
     * Handle the Proposal "created" event.
     */
    public function created(Proposal $proposal): void
    {
        // 1. Pengguna mengajukan -> Notifikasi ke Staff
        $staffUsers = User::where('role', User::ROLE_STAFF)->get();
        
        Notification::make()
            ->title('E-Proposal Baru Masuk')
            ->body("Proposal dari {$proposal->nama_pengaju} menunggu verifikasi awal.")
            ->icon('heroicon-o-document-plus')
            ->iconColor('primary')
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(fn() => "/admin/proposals/{$proposal->id}/edit")
                    ->button(),
            ])
            ->sendToDatabase($staffUsers);

        // 2. Notifikasi ke pengguna bahwa proposal telah diterima
        if ($proposal->user) {
            Notification::make()
                ->title('Proposal Berhasil Diajukan')
                ->body('Terima kasih, proposal Anda telah kami terima dan akan segera ditinjau oleh tim kami.')
                ->icon('heroicon-o-document-check')
                ->iconColor('success')
                ->sendToDatabase($proposal->user);
        }
    }

    /**
     * Handle the Proposal "updated" event.
     */
    public function updated(Proposal $proposal): void
    {
        if ($proposal->isDirty('status')) {
            $newStatus = $proposal->status;
            
            $targetAdmins = collect();
            $title = '';
            $body = '';
            $url = "/admin/proposals/{$proposal->id}/edit";

            // LOGIKA NOTIFIKASI BERTINGKAT ADMIN
            switch ($newStatus) {
                // Staff menerima -> Kirim ke JF PSU
                case Proposal::STATUS_DITERIMA:
                    $targetAdmins = User::where('role', User::ROLE_JF_PSU)->get();
                    $title = 'Proposal Perlu Verifikasi';
                    $body = "Proposal {$proposal->nama_pengaju} telah diterima Staff.";
                    break;

                // JF PSU memverifikasi -> Kirim ke Kabid
                case Proposal::STATUS_DIVERIFIKASI_JF:
                    $targetAdmins = User::where('role', User::ROLE_KABID)->get();
                    $title = 'Menunggu Persetujuan Kabid';
                    $body = "Proposal {$proposal->nama_pengaju} telah diverifikasi JF PSU.";
                    break;

                // Kabid menyetujui -> Kirim ke Kadis
                case Proposal::STATUS_DISETUJUI_KABID:
                    $targetAdmins = User::where('role', User::ROLE_KADIS)->get();
                    $title = 'Menunggu Persetujuan Akhir';
                    $body = "Proposal {$proposal->nama_pengaju} menunggu persetujuan Kepala Dinas.";
                    break;
            }

            // Kirim notifikasi ke Admin Selanjutnya (jika ada)
            if ($targetAdmins->isNotEmpty()) {
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
                    ->sendToDatabase($targetAdmins);
            }

            // LOGIKA NOTIFIKASI KE PENGGUNA (Author)
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
        }
    }
}