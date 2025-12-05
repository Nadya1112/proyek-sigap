<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProposalResource\Pages;
use App\Models\Proposal;
use App\Models\User; // Import User
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // Import Builder
use Illuminate\Database\Eloquent\Model; // Import Model
use Illuminate\Support\Facades\Auth; // Import Auth
use Illuminate\Support\Facades\Storage; // Import Storage

class ProposalResource extends Resource
{
    protected static ?string $model = Proposal::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Pelayanan Publik';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        /** @var User $user */
        $user = Auth::user();
        $query = parent::getEloquentQuery();

        // Super Admin dapat melihat semua proposal
        if ($user->isSuperAdmin()) {
            return $query;
        }

        // Filter proposal berdasarkan peran pengguna untuk alur bertingkat
        return $query->where(function (Builder $q) use ($user) {
            if ($user->isStaff()) {
                $q->where('status', Proposal::STATUS_DIAJUKAN);
            } elseif ($user->isJfPsu()) {
                $q->where('status', Proposal::STATUS_DITERIMA);
            } elseif ($user->isKabid()) {
                $q->where('status', Proposal::STATUS_DIVERIFIKASI_JF);
            } elseif ($user->isKadis()) {
                $q->where('status', Proposal::STATUS_DISETUJUI_KABID);
            }
        });
    }

    // Helper function untuk cek hak ubah status
    private static function canUpdateStatus(User $user, ?string $currentStatus): bool
    {
        if (!$currentStatus) return false;
        if ($user->isSuperAdmin()) return true; // Super admin bisa kapan saja

        return match ($currentStatus) {
            Proposal::STATUS_DIAJUKAN => $user->isStaff(),
            Proposal::STATUS_DITERIMA => $user->isJfPsu(),
            Proposal::STATUS_DIVERIFIKASI_JF => $user->isKabid(),
            Proposal::STATUS_DISETUJUI_KABID => $user->isKadis(),
            default => false,
        };
    }
    
    // Helper function untuk cek hak hapus
    private static function canDeleteAccess(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isJfPsu() || $user->isKabid() || $user->isKadis();
    }

    public static function form(Form $form): Form
    {
        /** @var User $user */
        $user = Auth::user();

        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengaju')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_pengaju')->disabled(),
                        Forms\Components\TextInput::make('kontak_pengaju')->disabled(),
                        Forms\Components\Select::make('user_id')->relationship('user', 'name')->disabled()->label('Akun Pengaju'),
                        Forms\Components\Select::make('kompleks_id')->relationship('komplek', 'nama_komplek')->disabled()->label('Komplek Perumahan'),
                        Forms\Components\Textarea::make('alamat')->disabled()->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Detail Proposal')
                    ->schema([
                        Forms\Components\Textarea::make('catatan')->label('Catatan dari Pengguna')->disabled()->columnSpanFull(),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('unduh_proposal')
                                ->label('Unduh Dokumen Proposal')
                                ->icon('heroicon-o-arrow-down-tray')
                                ->color('primary')
                                ->url(fn ($record) => $record?->proposal ? Storage::disk('public')->url($record->proposal) : null, true)
                                ->visible(fn ($record) => !empty($record?->proposal)),
                        ])->label('Dokumen Proposal'),
                    ]),
                
                Forms\Components\Section::make('Tindak Lanjut')
                    // Hanya tampil jika user punya hak update status
                    ->visible(fn (?Model $record) => $user->isSuperAdmin() || ($record && self::canUpdateStatus($user, $record->status)))
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(function (?Model $record) use ($user) {
                                if (!$record) return [];
                                $currentStatus = $record->status;
                                $options = [$currentStatus => "Saat ini: $currentStatus"]; // Status saat ini

                                // Super Admin bisa melihat semua opsi
                                if ($user->isSuperAdmin()) {
                                    return [
                                        Proposal::STATUS_DIAJUKAN => 'Diajukan',
                                        Proposal::STATUS_DITERIMA => 'Diterima (Staff)',
                                        Proposal::STATUS_DIVERIFIKASI_JF => 'Diverifikasi (JF PSU)',
                                        Proposal::STATUS_DISETUJUI_KABID => 'Setujui (Kabid)',
                                        Proposal::STATUS_DISETUJUI_KADIS => 'Setujui Final (Kadis)',
                                        Proposal::STATUS_DITOLAK => 'Tolak',
                                    ];
                                }

                                // Logika alur status berdasarkan role
                                switch ($currentStatus) {
                                    case Proposal::STATUS_DIAJUKAN:
                                        if ($user->isStaff()) {
                                            $options[Proposal::STATUS_DITERIMA] = 'Terima';
                                            $options[Proposal::STATUS_DITOLAK] = 'Tolak';
                                        }
                                        break;
                                    case Proposal::STATUS_DITERIMA:
                                        if ($user->isJfPsu()) {
                                            $options[Proposal::STATUS_DIVERIFIKASI_JF] = 'Verifikasi';
                                            $options[Proposal::STATUS_DITOLAK] = 'Tolak';
                                        }
                                        break;
                                    case Proposal::STATUS_DIVERIFIKASI_JF:
                                        if ($user->isKabid()) {
                                            $options[Proposal::STATUS_DISETUJUI_KABID] = 'Setujui';
                                            $options[Proposal::STATUS_DITOLAK] = 'Tolak';
                                        }
                                        break;
                                    case Proposal::STATUS_DISETUJUI_KABID:
                                        if ($user->isKadis()) {
                                            $options[Proposal::STATUS_DISETUJUI_KADIS] = 'Setujui Final';
                                            $options[Proposal::STATUS_DITOLAK] = 'Tolak';
                                        }
                                        break;
                                }
                                return $options;
                            })
                            ->required(),
                        
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Catatan Admin (Internal)')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pengaju')->searchable(),
                Tables\Columns\TextColumn::make('komplek.nama_komplek')->label('Nama Komplek')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Proposal::STATUS_DIAJUKAN => 'warning',
                        Proposal::STATUS_DITERIMA => 'info',
                        Proposal::STATUS_DIVERIFIKASI_JF => 'primary',
                        Proposal::STATUS_DISETUJUI_KABID => 'success',
                        Proposal::STATUS_DISETUJUI_KADIS => 'success',
                        Proposal::STATUS_DITOLAK => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label('Tanggal Masuk'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // PERUBAHAN: Tombol Delete hanya terlihat oleh Super Admin
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $user) => $user->isSuperAdmin()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // PERUBAHAN: Tombol Bulk Delete hanya terlihat oleh Super Admin
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (User $user) => $user->isSuperAdmin()),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProposals::route('/'),
            // Menggunakan halaman EditProposal yang sudah dimodifikasi
            'edit' => Pages\EditProposal::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool { return false; }

// PERUBAHAN: Hanya Super Admin yang bisa menghapus
    public static function canDelete(Model $record): bool
    { return Auth::user()->isSuperAdmin(); }
    
    public static function canDeleteAny(): bool
    { return Auth::user()->isSuperAdmin(); }
    
    // PERUBAHAN: Edit tetap bisa, sesuai logika status
    public static function canEdit(Model $record): bool
    {
        /** @var User $user */
        $user = Auth::user();
        return self::canUpdateStatus($user, $record->status);
    }
}