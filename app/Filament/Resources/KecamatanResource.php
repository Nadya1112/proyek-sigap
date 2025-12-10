<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KecamatanResource\Pages;
use App\Filament\Resources\KecamatanResource\RelationManagers;
use App\Models\Kecamatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\CodeEditor;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\DeleteBulkAction;

class KecamatanResource extends Resource
{
    protected static ?string $model = Kecamatan::class;
    protected static ?string $navigationGroup = 'Informasi FASUM';
    protected static ?string $title = 'Kecamatan';
    protected static ?string $navigationLabel = 'Kecamatan';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kecamatan')
                    ->label('Kecamatan')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),
                
                ColorPicker::make('warna')
                    ->label('Warna Wilayah')
                    ->required(),

                CodeEditor::make('geometri')
                    ->label('Data Geometri (GeoJSON)')
                    ->json()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kecamatan')
                    ->label('Kecamatan')
                    ->searchable(),
                
                Tables\Columns\ColorColumn::make('warna')
                    ->label('Warna'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
            'index' => Pages\ListKecamatans::route('/'),
            'create' => Pages\CreateKecamatan::route('/create'),
            'edit' => Pages\EditKecamatan::route('/{record}/edit'),
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