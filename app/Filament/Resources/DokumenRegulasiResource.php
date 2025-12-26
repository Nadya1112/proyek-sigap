<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenRegulasiResource\Pages;
use App\Models\Regulasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\HtmlString; // Penting untuk render HTML

class DokumenRegulasiResource extends Resource
{
    protected static ?string $model = Regulasi::class;
    protected static ?string $slug = 'dokumen-regulasi';
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationLabel = 'Regulasi';
    protected static ?string $navigationGroup = 'Manajemen Admin';
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
                
                Forms\Components\Select::make('jenis_dokumen')
                    ->options([
                        'Perda' => 'Perda (Peraturan Daerah)',
                        'Perkada' => 'Perkada (Peraturan Kepala Daerah)',
                        'SOP' => 'SOP (Standar Operasional Prosedur)',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),
                    
                Forms\Components\FileUpload::make('path')
                    ->label('File Dokumen')
                    ->required(fn (string $context): bool => $context === 'create')
                    ->disk('public')
                    ->directory('regulasi')
                    ->storeFileNamesIn('nama_file_asli')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->maxSize(10240)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('tipe_file', $state->getClientOriginalExtension());
                            $set('ukuran_file', round($state->getSize() / 1024));
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
                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(50),
                Tables\Columns\TextColumn::make('tahun')
                    ->sortable(),
                
                // KOLOM "JENIS" SUDAH DIHAPUS SESUAI PERMINTAAN
                
                Tables\Columns\TextColumn::make('tipe_file')
                    ->label('Ext')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('ukuran_file')
                    ->label('Ukuran')
                    ->numeric()
                    ->suffix(' KB')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_dokumen')
                    ->options([
                        'Perda' => 'Perda',
                        'Perkada' => 'Perkada',
                        'SOP' => 'SOP',
                        'Lainnya' => 'Lainnya',
                    ]),
            ])
            ->actions([

                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->isSuperAdmin()), 

                // 3. Download Action
                Tables\Actions\Action::make('unduh')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn (Regulasi $record) => Storage::disk('public')->download($record->path, $record->nama_file_asli)),

                // 4. Delete Action
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->isSuperAdmin())
                    ->after(function (Regulasi $record) {
                        if ($record->path && Storage::disk('public')->exists($record->path)) {
                            Storage::disk('public')->delete($record->path);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->isSuperAdmin()),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokumenRegulasis::route('/'),
            'create' => Pages\CreateDokumenRegulasi::route('/create'),
            'edit' => Pages\EditDokumenRegulasi::route('/{record}/edit'),
        ];
    }

    // --- HAK AKSES ---
    public static function canCreate(): bool
    { return Auth::user()->isSuperAdmin(); }

    public static function canEdit(Model $record): bool
    { return Auth::user()->isSuperAdmin(); }

    public static function canDelete(Model $record): bool
    { return Auth::user()->isSuperAdmin(); }

    public static function canDeleteAny(): bool
    { return Auth::user()->isSuperAdmin(); }
}