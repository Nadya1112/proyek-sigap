<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaduanResource\Pages;
use App\Models\Pengaduan;
use App\Models\User; // Import User
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model; // Import Model
use Illuminate\Support\Facades\Auth; // Import Auth

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;
    protected static ?string $navigationGroup = 'Pelayanan Publik';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    // Helper function untuk cek hak ubah status
    private static function canUpdateStatus(User $user, ?string $currentStatus): bool
    {
        if (!$currentStatus) return false;
        if ($user->isSuperAdmin()) return true;

        return match ($currentStatus) {
            Pengaduan::STATUS_DITERIMA => $user->isStaff() || $user->isJfPsu(),
            Pengaduan::STATUS_DIVERIFIKASI_JF => $user->isJfPsu(),
            Pengaduan::STATUS_DIPROSES => $user->isJfPsu(),
            default => false, // Status final (Selesai/Ditolak)
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
                Forms\Components\Section::make('Informasi Pelapor')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pelapor')->disabled(),
                        Forms\Components\TextInput::make('kontak_pelapor')->disabled(),
                        Forms\Components\Select::make('user_id')->relationship('user', 'name')->disabled()->label('Akun Pelapor'),
                    ])->columns(3),
                
                Forms\Components\Section::make('Detail Pengaduan')
                    ->schema([
                        Forms\Components\Textarea::make('isi_pengaduan')->disabled()->columnSpanFull(),
                        Forms\Components\Image::make('bukti_foto')->disabled()->label('Bukti Foto')->disk('public'),
                    ]),

                Forms\Components\Section::make('Tindak Lanjut Admin')
                    ->visible(fn (?Model $record) => $user->isSuperAdmin() || ($record && self::canUpdateStatus($user, $record->status)))
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(function (?Model $record) use ($user) {
                                if (!$record) return [];
                                $currentStatus = $record->status;
                                $options = [$currentStatus => "Saat ini: $currentStatus"];

                                if ($user->isSuperAdmin()) {
                                    return [
                                        Pengaduan::STATUS_DITERIMA => 'Diterima',
                                        Pengaduan::STATUS_DIVERIFIKASI_JF => 'Diverifikasi (JF PSU)',
                                        Pengaduan::STATUS_DIPROSES => 'Diproses',
                                        Pengaduan::STATUS_SELESAI => 'Selesai',
                                        Pengaduan::STATUS_DITOLAK => 'Ditolak',
                                    ];
                                }

                                // Logika alur status berdasarkan role
                                switch ($currentStatus) {
                                    case Pengaduan::STATUS_DITERIMA:
                                        if ($user->isStaff() || $user->isJfPsu()) $options[Pengaduan::STATUS_DITOLAK] = 'Tolak';
                                        if ($user->isJfPsu()) $options[Pengaduan::STATUS_DIVERIFIKASI_JF] = 'Verifikasi (JF PSU)';
                                        break;
                                    case Pengaduan::STATUS_DIVERIFIKASI_JF:
                                        if ($user->isJfPsu()) {
                                             $options[Pengaduan::STATUS_DITOLAK] = 'Tolak';
                                             $options[Pengaduan::STATUS_DIPROSES] = 'Proses (Tindak Lanjut)';
                                        }
                                        break;
                                    case Pengaduan::STATUS_DIPROSES:
                                        if ($user->isJfPsu()) $options[Pengaduan::STATUS_SELESAI] = 'Selesai';
                                        break;
                                }
                                return $options;
                            })
                            ->required(),
                        Forms\Components\Textarea::make('catatan_admin') // Kolom baru
                            ->label('Catatan Admin (Internal)')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pelapor')->searchable(),
                Tables\Columns\ImageColumn::make('bukti_foto')->label('Bukti')->disk('public'),
                Tables\Columns\TextColumn::make('isi_pengaduan')->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Pengaduan::STATUS_DITERIMA => 'primary',
                        Pengaduan::STATUS_DIVERIFIKASI_JF => 'info',
                        Pengaduan::STATUS_DIPROSES => 'warning',
                        Pengaduan::STATUS_SELESAI => 'success',
                        Pengaduan::STATUS_DITOLAK => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label('Tanggal Masuk'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $user) => self::canDeleteAccess($user)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (User $user) => self::canDeleteAccess($user)),
                ]),
            ]);
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
    { return self::canDeleteAccess(Auth::user()); }
    
    public static function canDeleteAny(): bool
    { return self::canDeleteAccess(Auth::user()); }
    
    // Nonaktifkan 'edit' untuk Staff
    public static function canEdit(Model $record): bool
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->isSuperAdmin()) return true;
        if ($user->isStaff()) {
            return $record->status === Pengaduan::STATUS_DITERIMA;
        }
        return $user->isJfPsu();
    }
}