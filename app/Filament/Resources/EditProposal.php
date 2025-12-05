<?php

namespace App\Filament\Resources\ProposalResource\Pages;

use App\Filament\Resources\ProposalResource;
use App\Models\Proposal;
use App\Models\User;
use App\Notifications\ProposalUpdatedNotification;
use Filament\Actions;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Notification;

class EditProposal extends EditRecord
{
    protected static string $resource = ProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn ($record) => auth()->user()->isSuperAdmin()),
        ];
    }

    /**
     * Logika setelah data disimpan.
     * Mengirim notifikasi ke pengaju dan ke grup admin selanjutnya.
     */
    protected function afterSave(): void
    {
        $proposal = $this->getRecord();
        $newStatus = $this->data['status'];
        $originalStatus = $this->getRecord()->getOriginal('status');

        // Kirim notifikasi hanya jika status berubah
        if ($newStatus !== $originalStatus) {
            // 1. Notifikasi untuk Pengaju Proposal
            $proposal->user->notify(new ProposalUpdatedNotification($proposal));

            // 2. Notifikasi Bertingkat untuk Grup Admin Selanjutnya
            $adminGroupToNotify = match ($newStatus) {
                Proposal::STATUS_DITERIMA => User::ROLE_JF_PSU,
                Proposal::STATUS_DIVERIFIKASI_JF => User::ROLE_KABID,
                Proposal::STATUS_DISETUJUI_KABID => User::ROLE_KADIS,
                default => null, // Tidak ada notifikasi admin untuk status lain (ditolak, disetujui final, dll)
            };

            if ($adminGroupToNotify) {
                $admins = User::where('role', $adminGroupToNotify)->get();
                if ($admins->isNotEmpty()) {
                    $message = "Ada proposal baru yang perlu ditindaklanjuti dengan status: {$newStatus}.";
                    Notification::send($admins, FilamentNotification::make()->title('Proposal Baru')->body($message)->toDatabase());
                }
            }
        }
    }
}
