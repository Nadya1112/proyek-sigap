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

// Import komponen form yang baru
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\CodeEditor; // Lebih baik untuk JSON

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
                    ->columnSpanFull(), // Buat field ini jadi lebar penuh
                
                // --- FIELD WARNA ---
                ColorPicker::make('warna')
                    ->label('Warna Wilayah')
                    ->required(),

                // --- FIELD GEOMETRI ---
                CodeEditor::make('geometri')
                    ->label('Data Geometri (GeoJSON)')
                    ->json() // Memberi tahu editor ini adalah format JSON
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
                
                // --- TAMPILKAN WARNA DI TABEL ---
                Tables\Columns\ColorColumn::make('warna')
                    ->label('Warna'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

} // <-- Ini adalah kurung kurawal penutup untuk 'class KecamatanResource'