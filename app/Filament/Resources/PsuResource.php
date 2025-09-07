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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kompleks_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jenis_psu')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('foto_psu')
                    ->maxLength(255),
                Forms\Components\TextInput::make('panjang_jalan')
                    ->numeric(),
                Forms\Components\TextInput::make('lebar_jalan')
                    ->numeric(),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kompleks_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_psu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('foto_psu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('panjang_jalan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lebar_jalan')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListPsus::route('/'),
            'create' => Pages\CreatePsu::route('/create'),
            'edit' => Pages\EditPsu::route('/{record}/edit'),
        ];
    }
}
