<?php
namespace App\Filament\Resources;
use App\Filament\Resources\KomplekResource\Pages;
use App\Models\Komplek;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KomplekResource extends Resource {
    protected static ?string $model = Komplek::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    public static function form(Form $form): Form {
        return $form->schema([
            Forms\Components\TextInput::make('nama_komplek')->required(),
            Forms\Components\Select::make('kelurahan_id')->relationship('kelurahan', 'nama_kelurahan')->searchable()->preload()->required(),
            Forms\Components\Textarea::make('alamat')->required()->columnSpanFull(),
            Forms\Components\FileUpload::make('foto_komplek')->image()->directory('foto-komplek'),
            Forms\Components\TextInput::make('jumlah_sertifikat')->numeric()->default(0),
            Forms\Components\Select::make('status_aset')->options(['Sudah Diserahkan' => 'Sudah Diserahkan', 'Belum Diserahkan' => 'Belum Diserahkan', 'Proses Penyerahan' => 'Proses Penyerahan',])->required(),
        ]);
    }
    public static function table(Table $table): Table {
        return $table->columns([
                Tables\Columns\ImageColumn::make('foto_komplek')->circular(),
                Tables\Columns\TextColumn::make('nama_komplek')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kelurahan.nama_kelurahan')->label('Kelurahan')->sortable(),
                Tables\Columns\TextColumn::make('status_aset')->badge()->color(fn (string $state): string => match ($state) {
                    'Sudah Diserahkan' => 'success', 'Belum Diserahkan' => 'danger', 'Proses Penyerahan' => 'warning',
                }),
            ])
            ->actions([Tables\Actions\EditAction::class, Tables\Actions\DeleteAction::class,])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::class,]),]);
    }
    public static function getPages(): array {
        return [
            'index' => Pages\ListKompleks::class,
            'create' => Pages\CreateKomplek::class,
            'edit' => Pages\EditKomplek::class,
        ];
    }
}