<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PsuResource\Pages;
use App\Filament\Resources\PsuResource\RelationManagers;
use App\Models\Psu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PsuResource extends Resource
{
    protected static ?string $model = Psu::class;
    protected static ?string $navigationGroup = 'Informasi FASUM';
    protected static ?string $title = 'PSU';
    protected static ?string $navigationLabel = 'PSU';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('kompleks_id')
            ->relationship('komplek', 'nama_komplek')
            ->searchable()
            ->preload()
            ->required()
            ->label('Komplek Perumahan'),
            Forms\Components\TextInput::make('jenis_psu')
            ->label('Jenis PSU')
            ->required()
            ->maxLength(255)
            ->placeholder('Contoh: Jalan, Saluran Drainase, Taman'),
            Forms\Components\FileUpload::make('foto_psu')
            ->image()
            ->directory('foto-psu')
            ->label('Foto PSU'),
            Forms\Components\Fieldset::make('Detail Ukuran (Opsional)')
            ->schema([
                Forms\Components\TextInput::make('panjang_jalan')
                ->label('Panjang Jalan')
                ->numeric()
                ->suffix('meter'),
                Forms\Components\TextInput::make('lebar_jalan')
                ->label('Lebar Jalan')
                ->numeric()
                ->suffix('meter'),
            ])->columns(2),
            Forms\Components\Textarea::make('keterangan')
            ->label('Keterangan')
            ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\ImageColumn::make('foto_psu')
                ->label('Foto')
                ->circular(),
                Tables\Columns\TextColumn::make('komplek.nama_komplek')
                ->label('Komplek Perumahan')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('jenis_psu')
                ->searchable(),
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
            'index' => Pages\ListPsus::route('/'),
            'create' => Pages\CreatePsu::route('/create'),
            'edit' => Pages\EditPsu::route('/{record}/edit'),
        ];
    }
}
