<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenRegulasiResource\Pages;
use App\Models\Regulasi; // Gunakan model Regulasi dari database
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DokumenRegulasiResource extends Resource
{
    protected static ?string $model = Regulasi::class; // Hubungkan ke model Regulasi
    protected static ?string $slug = 'dokumen-regulasi';
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationLabel = 'Dokumen Regulasi';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('tahun')
                    ->numeric(),
                Forms\Components\FileUpload::make('path')
                    ->label('File Dokumen')
                    ->required(fn (string $context): bool => $context === 'create')
                    ->disk('public') // Simpan di disk 'public' (storage/app/public)
                    ->directory('regulasi') // Simpan di dalam folder 'regulasi'
                    ->storeFileNamesIn('nama_file_asli') // Simpan nama asli file
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->maxSize(10240) // 10MB
                    // Isi tipe dan ukuran file secara otomatis
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('tipe_file', $state->getClientOriginalExtension());
                            $set('ukuran_file', round($state->getSize() / 1024)); // Ukuran dalam KB
                        }
                    })
                    ->columnSpanFull(),
                
                Forms\Components\Hidden::make('nama_file_asli'),
                Forms\Components\Hidden::make('tipe_file'),
                Forms\Components\Hidden::make('ukuran_file'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tahun')->sortable(),
                Tables\Columns\TextColumn::make('tipe_file')->label('Tipe')->badge(),
                Tables\Columns\TextColumn::make('ukuran_file')->label('Ukuran (KB)')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Regulasi $record) {
                        // Setelah record dihapus dari database, hapus juga filenya dari storage
                        if ($record->path) {
                            Storage::disk('public')->delete($record->path);
                        }
                    }),
                Tables\Actions\Action::make('unduh')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Regulasi $record) => Storage::disk('public')->url($record->path), true),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        // Hubungkan ke halaman standar Filament
        return [
            'index' => Pages\ListDokumenRegulasis::route('/'),
            'create' => Pages\CreateDokumenRegulasi::route('/create'),
            'edit' => Pages\EditDokumenRegulasi::route('/{record}/edit'),
        ];
    }
}