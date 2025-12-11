<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelurahanResource\Pages;
use App\Filament\Resources\KelurahanResource\RelationManagers;
use App\Models\Kelurahan;
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
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\DeleteBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class KelurahanResource extends Resource
{
    protected static ?string $model = Kelurahan::class;
    protected static ?string $navigationGroup = 'Informasi FASUM';
    protected static ?string $title = 'Kelurahan';
    protected static ?string $navigationLabel = 'Kelurahan';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('kecamatan_id')
            ->relationship('kecamatan', 'nama_kecamatan')
            ->label('Kecamatan')
            ->preload()
            ->required(),
            Forms\Components\TextInput::make('nama_kelurahan')
            ->label('Kelurahan')
            ->required()
            ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Kecamatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_kelurahan')
                    ->label('Kelurahan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kompleks_count')
                    ->label('Jumlah Komplek')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                ExportAction::make()->visible(fn () => auth()->user()->isSuperAdmin()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ExportBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('kompleks');
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
            'index' => Pages\ListKelurahans::route('/'),
            'create' => Pages\CreateKelurahan::route('/create'),
            'edit' => Pages\EditKelurahan::route('/{record}/edit'),
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
