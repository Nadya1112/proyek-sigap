<?php

namespace App\Observers;

use App\Models\Pengaduan;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class PengaduanObserver
{
    /**
     * Handle the Pengaduan "created" event.
     */
    public function created(Pengaduan $pengaduan): void
    {
        // 1. Pengguna mengajukan -> Notifikasi ke Staff
        $staffUsers = User::where('role', User::ROLE_STAFF)->get();
        
        Notification::make()
            ->title('Pengaduan Baru Masuk')
            ->body("Pengaduan dari {$pengaduan->nama_pelapor} menunggu untuk tindak lanjut.")
            ->icon('heroicon-o-inbox-arrow-down') // Ikon keren
            ->iconColor('primary')
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(fn() => "/admin/pengaduans/{$pengaduan->id}/edit") // Link ke halaman detail
                    ->button(),
            ])
            ->sendToDatabase($staffUsers); // Kirim ke database notifikasi Staff

        // 2. Notifikasi ke pengguna bahwa pengaduan telah diterima
        if ($pengaduan->user) {
            Notification::make()
                ->title('Pengaduan Berhasil Diajukan')
                ->body('Terima kasih, pengaduan Anda telah kami terima dan akan segera ditinjau.')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->iconColor('success')
                ->sendToDatabase($pengaduan->user);
        }
    }

    /**
     * Handle the Pengaduan "updated" event.
     */
    public function updated(Pengaduan $pengaduan): void
    {
        if ($pengaduan->isDirty('status')) {
            $newStatus = $pengaduan->status;
            
            $targetAdmins = collect();
            $title = '';
            $body = '';
            $url = "/admin/pengaduans/{$pengaduan->id}/edit";

            // LOGIKA NOTIFIKASI BERTINGKAT
            switch ($newStatus) {
                // Staff menerima -> Kirim ke JF PSU
                case Pengaduan::STATUS_DITERIMA:
                    $targetAdmins = User::where('role', User::ROLE_JF_PSU)->get();
                    $title = 'Pengaduan Perlu Verifikasi';
                    $body = "Pengaduan {$pengaduan->nama_pelapor} telah diterima Staff.";
                    break;

                // JF PSU memverifikasi -> Kirim ke Kabid
                case Pengaduan::STATUS_DIVERIFIKASI_JF:
                    $targetAdmins = User::where('role', User::ROLE_KABID)->get();
                    $title = 'Menunggu Persetujuan Kabid';
                    $body = "Pengaduan {$pengaduan->nama_pelapor} telah diverifikasi JF PSU.";
                    break;

                // Kabid menyetujui -> Kirim ke Kadis
                case Pengaduan::STATUS_DISETUJUI_KABID:
                    $targetAdmins = User::where('role', User::ROLE_KADIS)->get();
                    $title = 'Menunggu Persetujuan Akhir';
                    $body = "Pengaduan {$pengaduan->nama_pelapor} menunggu persetujuan Kepala Dinas.";
                    break;
            }

            // Kirim notifikasi ke Admin Selanjutnya
            if ($targetAdmins->isNotEmpty()) {
                Notification::make()
                    ->title($title)
                    ->body($body)
                    ->icon('heroicon-o-bell')
                    ->warning() // Warna kuning/orange
                    ->actions([
                        Action::make('view')
                            ->label('Tinjau')
                            ->url($url)
                            ->markAsRead(), // Otomatis tandai terbaca saat diklik (seperti di video)
                    ])
                    ->sendToDatabase($targetAdmins);
            }

            // LOGIKA NOTIFIKASI KE PENGGUNA (Author)
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
        }
    }
}