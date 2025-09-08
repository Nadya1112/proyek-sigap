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

class KomplekResource extends Resource
{
    protected static ?string $model = Komplek::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nomor'), // DITAMBAHKAN
            Forms\Components\TextInput::make('nama_komplek')->required(),
            Forms\Components\Select::make('kelurahan_id')
                ->relationship('kelurahan', 'nama_kelurahan')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\Textarea::make('alamat') // DIHAPUS ->required()
                ->columnSpanFull(),
            Forms\Components\FileUpload::make('foto_komplek')
            ->image()
            ->directory('foto-komplek'),
            Forms\Components\TextInput::make('jumlah_sertifikat')
            ->nullable() // DIHAPUS ->required()
            ->default(0),
            Forms\Components\Select::make('status_aset')
            ->options([
                'Sudah Diserahkan' => 'Sudah Diserahkan',
                'Belum Diserahkan' => 'Belum Diserahkan',
                'Proses Penyerahan' => 'Proses Penyerahan',
            ])
            ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')
                ->searchable()
                ->sortable(), // DITAMBAHKAN
                Tables\Columns\ImageColumn::make('foto_komplek')
                ->circular(),
                Tables\Columns\TextColumn::make('nama_komplek')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('kelurahan.nama_kelurahan')
                ->label('Kelurahan')
                ->sortable(),
                Tables\Columns\TextColumn::make('status_aset')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Sudah Diserahkan' => 'success',
                    'Belum Diserahkan' => 'danger',
                    'Proses Penyerahan' => 'warning',
                    default => 'gray',
                }),
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
}
