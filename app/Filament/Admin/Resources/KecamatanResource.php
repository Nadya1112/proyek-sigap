<?php
namespace App\Filament\Resources;
use App\Filament\Resources\KecamatanResource\Pages;
use App\Models\Kecamatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KecamatanResource extends Resource {
    protected static ?string $model = Kecamatan::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    public static function form(Form $form): Form {
        return $form->schema([
            Forms\Components\TextInput::make('nama_kecamatan')->required()->unique(ignoreRecord: true),
        ]);
    }
    public static function table(Table $table): Table {
        return $table->columns([Tables\Columns\TextColumn::make('nama_kecamatan')->searchable()])
            ->actions([Tables\Actions\EditAction::class, Tables\Actions\DeleteAction::class,])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::class,]),]);
    }
    public static function getPages(): array {
        return [
            'index' => Pages\ListKecamatans::class,
            'create' => Pages\CreateKecamatan::class,
            'edit' => Pages\EditKecamatan::class,
        ];
    }
}