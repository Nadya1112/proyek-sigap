<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KomplekBaruResource\Pages;
use App\Models\KomplekBaru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\Action; // Import Action khusus

class KomplekBaruResource extends Resource
{
    protected static ?string $model = KomplekBaru::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Pengajuan Komplek Baru';
    protected static ?string $navigationGroup = 'Manajemen Admin';
    protected static ?int $navigationSort = 99;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pengajuan')
                    ->schema([
                        Forms\Components\TextInput::make('nama_komplek')->label('Nama Perumahan')->disabled(),
                        Forms\Components\TextInput::make('alamat')->label('Alamat')->disabled(),
                        Forms\Components\TextInput::make('nomor_hp')->label('No HP Pengaju')->disabled(),
                        Forms\Components\TextInput::make('kecamatan.nama_kecamatan')->label('Kecamatan')->disabled(),
                        Forms\Components\TextInput::make('kelurahan.nama_kelurahan')->label('Kelurahan')->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Proses Admin')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'Pending' => 'Pending',
                                'Diproses' => 'Diproses',
                                'Diterima' => 'Diterima (Setujui)',
                                'Ditolak' => 'Ditolak',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Catatan Admin')
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_komplek')->searchable()->label('Nama Komplek')->wrap(),
                // TAMBAHAN 1: Kolom Kecamatan
                Tables\Columns\TextColumn::make('kecamatan.nama_kecamatan')->label('Kecamatan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kelurahan.nama_kelurahan')->label('Kelurahan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nomor_hp')->label('No HP')->copyable(), // Fitur copyable agar mudah disalin
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'gray',
                        'Diproses' => 'warning',
                        'Diterima' => 'success',
                        'Ditolak' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Tanggal Ajuan')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Diproses' => 'Diproses',
                        'Diterima' => 'Diterima',
                        'Ditolak' => 'Ditolak',
                    ]),
            ])
            ->actions([
                // TAMBAHAN 2: Tombol WhatsApp
                Action::make('hubungi_wa')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(fn (KomplekBaru $record) => 'https://wa.me/' . preg_replace('/^0/', '62', preg_replace('/\D/', '', $record->nomor_hp)), true), // Konversi 08xx ke 628xx dan buka di tab baru

                Tables\Actions\EditAction::make()->label('Proses'),
                // TAMBAHAN 3: Tombol Delete (untuk semua admin, karena logic canDelete diubah)
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKomplekBarus::route('/'),
            'create' => Pages\CreateKomplekBaru::route('/create'),
            'edit' => Pages\EditKomplekBaru::route('/{record}/edit'),
        ];
    }
    
    // --- HAK AKSES ---
    public static function canViewAny(): bool { return true; } 
    public static function canCreate(): bool { return false; } 
    public static function canEdit(Model $record): bool { return true; } 
    
    // PERUBAHAN: Izinkan semua admin menghapus (return true)
    public static function canDelete(Model $record): bool { return true; } 
}