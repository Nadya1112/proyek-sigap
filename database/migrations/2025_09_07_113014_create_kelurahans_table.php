<?php
namespace App\Filament\Resources;
use App\Filament\Resources\KelurahanResource\Pages;
use App\Models\Kelurahan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KelurahanResource extends Resource {
    protected static ?string $model = Kelurahan::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    public static function form(Form $form): Form {
        return $form->schema([
            Forms\Components\Select::make('kecamatan_id')->relationship('kecamatan', 'nama_kecamatan')->searchable()->preload()->required(),
            Forms\Components\TextInput::make('nama_kelurahan')->required(),
        ]);
    }
    public static function table(Table $table): Table {
        return $table->columns([
                Tables\Columns\TextColumn::make('nama_kelurahan')->searchable(),
                Tables\Columns\TextColumn::make('kecamatan.nama_kecamatan')->searchable()->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::class, Tables\Actions\DeleteAction::class,])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::class,]),]);
    }
    public static function getPages(): array {
        return [
            'index' => Pages\ListKelurahans::class,
            'create' => Pages\CreateKelurahan::class,
            'edit' => Pages\EditKelurahan::class,
        ];
    }
}