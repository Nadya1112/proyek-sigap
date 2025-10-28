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
use Illuminate\Database\Eloquent\Model; // Import Model

class ProposalResource extends Resource
{
    protected static ?string $model = Proposal::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Pelayanan Publik';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
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
                                ->url(fn ($record) => $record?->proposal ? asset('storage/' . $record->proposal) : null, true)
                                ->visible(fn ($record) => !empty($record?->proposal)),
                        ])->label('Dokumen Proposal'),
                    ]),
                
                Forms\Components\Section::make('Tindakan Admin')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(function (?Model $record, \Illuminate\Contracts\Auth\Authenticatable $user) {
                                if (!$record) return []; // Kosong saat create
                                $currentStatus = $record->status;
                                $options = [$currentStatus => $currentStatus]; // Status saat ini

                                // Logika alur status berdasarkan role
                                switch ($currentStatus) {
                                    case Proposal::STATUS_DIAJUKAN:
                                        if ($user->isStaff() || $user->isJfPsu()) $options[Proposal::STATUS_DITOLAK] = 'Tolak';
                                        if ($user->isJfPsu()) $options[Proposal::STATUS_DIVERIFIKASI_JF] = 'Verifikasi (JF PSU)';
                                        break;
                                    case Proposal::STATUS_DIVERIFIKASI_JF:
                                        if ($user->isJfPsu() || $user->isKabid()) $options[Proposal::STATUS_DITOLAK] = 'Tolak';
                                        if ($user->isKabid()) $options[Proposal::STATUS_DISETUJUI_KABID] = 'Setujui (Kabid)';
                                        break;
                                    case Proposal::STATUS_DISETUJUI_KABID:
                                        if ($user->isKabid() || $user->isKadis()) $options[Proposal::STATUS_DITOLAK] = 'Tolak';
                                        if ($user->isKadis()) $options[Proposal::STATUS_DISETUJUI_KADIS] = 'Setujui Final (Kadis)';
                                        break;
                                }
                                return $options;
                            })
                            ->required()
                            ->disabled(function (?Model $record, \Illuminate\Contracts\Auth\Authenticatable $user) {
                                if (!$record) return true;
                                // Non-aktifkan jika status final atau jika user tidak punya hak
                                return match ($record->status) {
                                    Proposal::STATUS_DIAJUKAN => !$user->isStaff() && !$user->isJfPsu(),
                                    Proposal::STATUS_DIVERIFIKASI_JF => !$user->isKabid() && !$user->isJfPsu(),
                                    Proposal::STATUS_DISETUJUI_KABID => !$user->isKadis() && !$user->isKabid(),
                                    default => true, // Status Ditolak atau Disetujui Kadis
                                };
                            }),
                        
                        Forms\Components\Textarea::make('catatan_admin') // Kolom baru untuk catatan admin
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
                        Proposal::STATUS_DIVERIFIKASI_JF => 'info', // Baru
                        Proposal::STATUS_DISETUJUI_KABID => 'primary', // Baru
                        Proposal::STATUS_DISETUJUI_KADIS => 'success', // Status final
                        Proposal::STATUS_DITOLAK => 'danger',
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
                    // Hanya role tertentu yang bisa menghapus
                    ->visible(fn (\Illuminate\Contracts\Auth\Authenticatable $user) =>
                        $user->isJfPsu() || $user->isKabid() || $user->isKadis()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        // Hanya role tertentu yang bisa bulk delete
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
            'index' => Pages\ListProposals::route('/'),
            // 'create' => Pages\CreateProposal::route('/create'), // Tetap non-aktif
            'edit' => Pages\EditProposal::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool { return false; }
}