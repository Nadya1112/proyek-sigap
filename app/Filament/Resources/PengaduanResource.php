<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaduanResource\Pages;
use App\Models\Pengaduan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\DeleteBulkAction;

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;
    protected static ?string $navigationGroup = 'Pelayanan Publik';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->role, [
            'admin',
            'Staff',
            'JF PSU',
            'Kabid',
            'Kadis',
        ]);
    }

    // Helper function untuk cek hak ubah status sesuai alur baru
    private static function canUpdateStatus(User $user, ?string $currentStatus): bool
    {
        if (!$currentStatus) return false;
        if ($user->isSuperAdmin()) return true;

        // Alur status baru
        return match ($currentStatus) {
            Pengaduan::STATUS_DIAJUKAN => $user->isStaff(),
            Pengaduan::STATUS_DITERIMA => $user->isJfPsu(),
            Pengaduan::STATUS_DIVERIFIKASI_JF => $user->isKabid(),
            Pengaduan::STATUS_DISETUJUI_KABID => $user->isKadis(),
            default => false, // Status final (Disetujui Kadis, Ditolak) tidak bisa diubah
        };
    }
    
    public static function form(Form $form): Form
    {
        /** @var User $user */
        $user = Auth::user();
        
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelapor')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pelapor')->disabled(),
                        Forms\Components\TextInput::make('kontak_pelapor')->disabled(),
                        Forms\Components\Select::make('user_id')->relationship('user', 'name')->disabled()->label('Akun Pelapor'),
                    ])->columns(3),
                
                Forms\Components\Section::make('Detail Pengaduan')
                    ->schema([
                        Forms\Components\TextInput::make('judul_pengaduan')->disabled()->label('Judul Pengaduan')->columnSpanFull(),
                        Forms\Components\Textarea::make('isi_pengaduan')->disabled()->columnSpanFull(),
                        Forms\Components\FileUpload::make('bukti_foto')->image()->disabled()->label('Bukti Foto')->disk('public'),
                    ]),

                Forms\Components\Section::make('Tindak Lanjut')
                    ->visible(fn (?Model $record) => $user->isSuperAdmin() || ($record && self::canUpdateStatus($user, $record->status)))
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(function (?Model $record) use ($user) {
                                if (!$record) return [];
                                $currentStatus = $record->status;
                                $options = [$currentStatus => "Saat ini: $currentStatus"];

                                if ($user->isSuperAdmin()) {
                                    return [
                                        Pengaduan::STATUS_DIAJUKAN => 'Diajukan',
                                        Pengaduan::STATUS_DITERIMA => 'Diterima (Staff)',
                                        Pengaduan::STATUS_DIVERIFIKASI_JF => 'Diverifikasi (JF PSU)',
                                        Pengaduan::STATUS_DISETUJUI_KABID => 'Setujui (Kabid)',
                                        Pengaduan::STATUS_DISETUJUI_KADIS => 'Setujui Final (Kadis)',
                                        Pengaduan::STATUS_DITOLAK => 'Tolak',
                                    ];
                                }

                                // Logika alur status baru berdasarkan role
                                switch ($currentStatus) {
                                    case Pengaduan::STATUS_DIAJUKAN:
                                        if ($user->isStaff()) {
                                            $options[Pengaduan::STATUS_DITERIMA] = 'Terima';
                                            $options[Pengaduan::STATUS_DITOLAK] = 'Tolak';
                                        }
                                        break;
                                    case Pengaduan::STATUS_DITERIMA:
                                        if ($user->isJfPsu()) {
                                            $options[Pengaduan::STATUS_DIVERIFIKASI_JF] = 'Verifikasi';
                                            $options[Pengaduan::STATUS_DITOLAK] = 'Tolak';
                                        }
                                        break;
                                    case Pengaduan::STATUS_DIVERIFIKASI_JF:
                                        if ($user->isKabid()) {
                                            $options[Pengaduan::STATUS_DISETUJUI_KABID] = 'Setujui';
                                            $options[Pengaduan::STATUS_DITOLAK] = 'Tolak';
                                        }
                                        break;
                                    case Pengaduan::STATUS_DISETUJUI_KABID:
                                        if ($user->isKadis()) {
                                            $options[Pengaduan::STATUS_DISETUJUI_KADIS] = 'Setujui Final';
                                            $options[Pengaduan::STATUS_DITOLAK] = 'Tolak';
                                        }
                                        break;
                                }
                                return $options;
                            })
                            ->required(),
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Catatan (Internal)')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(self::getEloquentQuery()) 
            ->columns([
                Tables\Columns\TextColumn::make('nama_pelapor')->searchable(),
                Tables\Columns\TextColumn::make('judul_pengaduan')->label('Judul Pengaduan')->searchable()->limit(40),
                Tables\Columns\ImageColumn::make('bukti_foto')->label('Bukti')->disk('public'),
                Tables\Columns\TextColumn::make('isi_pengaduan')->label('Isi')->limit(30)->wrap()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Pengaduan::STATUS_DIAJUKAN => 'gray',        // Kuning/Orange - Menunggu
                        Pengaduan::STATUS_DITERIMA => 'primary',           // Biru Muda - Diterima Staff
                        Pengaduan::STATUS_DIVERIFIKASI_JF => 'info', // Biru - Diverifikasi JF
                        Pengaduan::STATUS_DISETUJUI_KABID => 'success',    // Abu-abu/Slate - Disetujui Kabid
                        Pengaduan::STATUS_DISETUJUI_KADIS => 'success', // Hijau - Final/Selesai
                        Pengaduan::STATUS_DITOLAK => 'danger',          // Merah - Ditolak
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label('Tanggal Masuk'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
           ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make()
                //     ->visible(fn () => auth()->user()->isSuperAdmin()),
                ForceDeleteAction::make()
                    ->label('Hapus Permanen')
                    ->visible(fn () => auth()->user()->isSuperAdmin()),
                // RestoreAction::make()
                //     ->visible(fn () => auth()->user()->isSuperAdmin()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->isSuperAdmin()),
                    ForceDeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->isSuperAdmin()),
                    RestoreBulkAction::make()
                        ->visible(fn () => auth()->user()->isSuperAdmin()),
                ]),
            ]);
    }
    
    
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $query = parent::getEloquentQuery();

        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->where(function (Builder $subQuery) use ($user) {
            if ($user->isKadis()) {
                $subQuery->where('status', Pengaduan::STATUS_DISETUJUI_KABID);
            } elseif ($user->isKabid()) {
                $subQuery->where('status', Pengaduan::STATUS_DIVERIFIKASI_JF);
            } elseif ($user->isJfPsu()) {
                $subQuery->where('status', Pengaduan::STATUS_DITERIMA);
            } elseif ($user->isStaff()) {
                $subQuery->where('status', Pengaduan::STATUS_DIAJUKAN);
            } else {
                $subQuery->whereRaw('1 = 0');
            }
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengaduans::route('/'),
            'edit' => Pages\EditPengaduan::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool { return false; }
    
    public static function canDelete(Model $record): bool
    { return Auth::user()->isSuperAdmin(); }
    
    public static function canDeleteAny(): bool
    { return Auth::user()->isSuperAdmin(); }
    
    public static function canEdit(Model $record): bool
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            return true;
        }

        return self::canUpdateStatus($user, $record->status);
    }
}