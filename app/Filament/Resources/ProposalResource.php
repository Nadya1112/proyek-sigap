<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProposalResource\Pages;
use App\Models\Proposal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProposalResource extends Resource
{
    protected static ?string $model = Proposal::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Pelayanan Publik';
    protected static ?int $navigationSort = 2; // Urutan di sidebar

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Bagian data pengaju dibuat tidak bisa diubah (disabled)
                Forms\Components\Section::make('Informasi Pengaju')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_pengaju')
                            ->disabled(),
                        Forms\Components\TextInput::make('kontak_pengaju')
                            ->disabled(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->disabled()
                            ->label('Akun Pengaju'),
                        Forms\Components\Select::make('kompleks_id')
                            ->relationship('komplek', 'nama_komplek')
                            ->disabled()
                            ->label('Komplek Perumahan'),
                        Forms\Components\Textarea::make('alamat')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
                
                // Bagian detail proposal dibuat tidak bisa diubah
                Forms\Components\Section::make('Detail Proposal')
                    ->schema([
                        Forms\Components\Textarea::make('catatan')
                            ->disabled()
                            ->columnSpanFull(),
                        // Link untuk mengunduh file, bukan mengunggah ulang
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('unduh_proposal')
                                ->label('Unduh Dokumen Proposal')
                                ->icon('heroicon-o-arrow-down-tray')
                                ->color('primary')
                                ->action(null) // Biarkan null agar tidak melakukan apa-apa
                                ->url(fn ($record) => asset('storage/' . $record->proposal), true), // Buka di tab baru
                        ])->label('Dokumen Proposal')
                          ->visible(fn ($record) => !empty($record?->proposal)), // Hanya tampil jika ada file
                        // =======================================================

                        Forms\Components\Select::make('status')
                            ->options([
                                'Diajukan' => 'Diajukan',
                                'Diverifikasi' => 'Diverifikasi',
                                'Disetujui' => 'Disetujui',
                                'Ditolak' => 'Ditolak',
                            ])
                            ->required(),
                        
                        Forms\Components\Textarea::make('catatan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pengaju')
                    ->searchable(),
                Tables\Columns\TextColumn::make('komplek.nama_komplek') // Menampilkan nama dari relasi
                    ->label('Nama Komplek')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Diajukan' => 'warning',
                        'Diverifikasi' => 'primary',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Masuk'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProposals::route('/'),
            'create' => Pages\CreateProposal::route('/create'),
            'edit' => Pages\EditProposal::route('/{record}/edit'),
        ];
    }

    /**
     * Menonaktifkan tombol "Create" agar admin tidak bisa membuat proposal dari dashboard.
     */
    public static function canCreate(): bool
    {
        return false;
    }
}