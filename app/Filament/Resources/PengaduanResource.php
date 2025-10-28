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

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;
    protected static ?string $navigationGroup = 'Pelayanan Publik';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function form(Form $form): Form
    {
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
                        Forms\Components\Image::make('bukti_foto') // Gunakan Image untuk preview
                            ->disk('public')
                            ->label('Bukti Foto'),
                    ]),

                Forms\Components\Section::make('Tindak Lanjut Admin')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(function (?Model $record, \Illuminate\Contracts\Auth\Authenticatable $user) {
                                if (!$record) return [];
                                $currentStatus = $record->status;
                                $options = [$currentStatus => $currentStatus];

                                // Asumsi alur: Diterima -> Diverifikasi -> Diproses -> Selesai
                                switch ($currentStatus) {
                                    case Pengaduan::STATUS_DITERIMA:
                                        if ($user->isStaff() || $user->isJfPsu()) $options[Pengaduan::STATUS_DITOLAK] = 'Tolak';
                                        if ($user->isJfPsu()) $options[Pengaduan::STATUS_DIVERIFIKASI_JF] = 'Verifikasi (JF PSU)';
                                        break;
                                    case Pengaduan::STATUS_DIVERIFIKASI_JF:
                                        if ($user->isJfPsu()) $options[Pengaduan::STATUS_DITOLAK] = 'Tolak';
                                        if ($user->isJfPsu()) $options[Pengaduan::STATUS_DIPROSES] = 'Proses (Tindak Lanjut)';
                                        break;
                                    case Pengaduan::STATUS_DIPROSES:
                                        if ($user->isJfPsu()) $options[Pengaduan::STATUS_SELESAI] = 'Selesai';
                                        break;
                                }
                                return $options;
                            })
                            ->required()
                            ->disabled(function (?Model $record, \Illuminate\Contracts\Auth\Authenticatable $user) {
                                if (!$record) return true;
                                return match ($record->status) {
                                    Pengaduan::STATUS_DITERIMA => !$user->isStaff() && !$user->isJfPsu(),
                                    Pengaduan::STATUS_DIVERIFIKASI_JF => !$user->isJfPsu(),
                                    Pengaduan::STATUS_DIPROSES => !$user->isJfPsu(),
                                    default => true, // Status final (Selesai/Ditolak)
                                };
                            }),
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
                        Pengaduan::STATUS_DIVERIFIKASI_JF => 'info', // Baru
                        Pengaduan::STATUS_DIPROSES => 'warning',
                        Pengaduan::STATUS_SELESAI => 'success',
                        Pengaduan::STATUS_DITOLAK => 'danger', // Baru
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label('Tanggal Masuk'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (\Illuminate\Contracts\Auth\Authenticatable $user) =>
                        $user->isJfPsu() || $user->isKabid() || $user->isKadis()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (\Illuminate\Contracts\Auth\Authenticatable $user) =>
                            $user->isJfPsu() || $user->isKabid() || $user->isKadis()
                        ),
                ]),
            ]);
    }
    
    public static function getRelations(): array { return []; }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengaduans::route('/'),
            // 'create' => Pages\CreatePengaduan::route('/create'), // Non-aktifkan jika perlu
            'edit' => Pages\EditPengaduan::route('/{record}/edit'),
        ];
    }
    
    // Tambahkan ini jika admin tidak boleh membuat pengaduan
    public static function canCreate(): bool { return false; }
}