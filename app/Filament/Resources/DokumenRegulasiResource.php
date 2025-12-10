<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenRegulasiResource\Pages;
use App\Models\Regulasi;
use App\Models\User; // Import User
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model; // Import Model
use Illuminate\Support\Facades\Auth; // Import Auth
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\DeleteBulkAction;

class DokumenRegulasiResource extends Resource
{
    protected static ?string $model = Regulasi::class; // Hubungkan ke model Regulasi
    protected static ?string $slug = 'dokumen-regulasi';
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationLabel = 'Dokumen Regulasi';
    protected static ?string $navigationGroup = 'Manajemen Admin';
    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()->isSuperAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\TextInput::make('tahun')->numeric(),
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
                Tables\Columns\TextColumn::make('judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tahun')->sortable(),
                Tables\Columns\TextColumn::make('tipe_file')->label('Tipe')->badge(),
                Tables\Columns\TextColumn::make('ukuran_file')->label('Ukuran (KB)')->numeric()->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn (User $user) => $user->isSuperAdmin()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $user) => $user->isSuperAdmin())
                    ->after(function (Regulasi $record) { /* ... (logika hapus file) ... */ }),
                ForceDeleteAction::make()
                    ->visible(fn (User $user) => $user->isSuperAdmin()),
                RestoreAction::make()
                    ->visible(fn (User $user) => $user->isSuperAdmin()),
                Tables\Actions\Action::make('unduh')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Regulasi $record) => Storage::disk('public')->url($record->path), true),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (User $user) => $user->isSuperAdmin()),
                    ForceDeleteBulkAction::make()
                        ->visible(fn (User $user) => $user->isSuperAdmin()),
                    RestoreBulkAction::make()
                        ->visible(fn (User $user) => $user->isSuperAdmin()),
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

    public static function canCreate(): bool
    { return Auth::user()->isSuperAdmin(); }

    public static function canEdit(Model $record): bool
    { return Auth::user()->isSuperAdmin(); }

    public static function canDelete(Model $record): bool
    { return Auth::user()->isSuperAdmin(); }

    public static function canDeleteAny(): bool
    { return Auth::user()->isSuperAdmin(); }
}