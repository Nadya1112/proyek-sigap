<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KomplekResource\Pages;
use App\Filament\Resources\KomplekResource\RelationManagers;
use App\Models\Komplek;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class KomplekResource extends Resource
{
    protected static ?string $model = Komplek::class;
    protected static ?string $navigationGroup = 'Informasi FASUM';
    protected static ?string $title = 'Komplek';
    protected static ?string $navigationLabel = 'Komplek';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama_komplek')->required(),
            Forms\Components\TextInput::make('nama_pengembang'),
            Forms\Components\Textarea::make('alamat_komplek')->columnSpanFull(),
            Forms\Components\Select::make('kelurahan_id')
                ->relationship('kelurahan', 'nama_kelurahan')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\FileUpload::make('foto_komplek')
                ->image()
                ->directory('foto-komplek'),
            Forms\Components\TextInput::make('jumlah_sertifikat')->numeric()->default(0),
            Forms\Components\TextInput::make('jumlah_unit')->default(0),
            Forms\Components\Select::make('status_aset')
                ->options([
                    'Sudah Diserahkan' => 'Sudah Diserahkan',
                    'Belum Diserahkan' => 'Belum Diserahkan',
                    'Proses Penyerahan' => 'Proses Penyerahan',
                ])
                ->required(),
            Forms\Components\TextInput::make('fasilitas_ibadah'),
            Forms\Components\TextInput::make('fasilitas_umum'),
            Forms\Components\TextInput::make('fasilitas_pendidikan'),
            Forms\Components\TextInput::make('fasilitas_kesehatan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto_komplek')
                    ->label('Foto Komplek')
                    ->circular(),
                Tables\Columns\TextColumn::make('nama_komplek')
                    ->label('Nama Komplek')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pengembang')
                    ->label('Nama Pengembang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alamat_komplek')
                    ->label('Alamat Komplek')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelurahan.nama_kelurahan')
                    ->label('Kelurahan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelurahan.kecamatan.nama_kecamatan')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_sertifikat')
                    ->label('Jumlah Sertifikat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_unit')
                    ->label('Jumlah Unit')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_aset')
                    ->label('Status Aset')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sudah Diserahkan' => 'success',
                        'Belum Diserahkan' => 'danger',
                        'Proses Penyerahan' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fasilitas_ibadah')
                    ->label('Fasilitas Ibadah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fasilitas_umum')
                    ->label('Fasilitas Umum')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fasilitas_pendidikan')
                    ->label('Fasilitas Pendidikan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fasilitas_kesehatan')
                    ->label('Fasilitas Kesehatan')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListKompleks::route('/'),
            'create' => Pages\CreateKomplek::route('/create'),
            'edit' => Pages\EditKomplek::route('/{record}/edit'),
        ];
    }

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

    public static function canCreate(): bool
{ return Auth::user()->isSuperAdmin(); }

public static function canEdit(Model $record): bool
{ return Auth::user()->isSuperAdmin(); }

public static function canDelete(Model $record): bool
{ return Auth::user()->isSuperAdmin(); }

public static function canDeleteAny(): bool
{ return Auth::user()->isSuperAdmin(); }
}
