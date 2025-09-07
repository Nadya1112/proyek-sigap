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
        return $form
            ->schema([
                Forms\Components\TextInput::make('kelurahan_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nama_komplek')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('foto_komplek')
                    ->maxLength(255),
                Forms\Components\TextInput::make('jumlah_sertifikat')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('status_aset')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelurahan_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_komplek')
                    ->searchable(),
                Tables\Columns\TextColumn::make('foto_komplek')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_sertifikat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
