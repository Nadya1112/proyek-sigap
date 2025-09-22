<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaduanResource\Pages;
use App\Filament\Resources\PengaduanResource\RelationManagers;
use App\Models\Pengaduan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;
    protected static ?string $navigationGroup = 'Pelayanan Publik';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelapor')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pelapor')->disabled(),
                        Forms\Components\TextInput::make('kontak_pelapor')->disabled(),
                        Forms\Components\Select::make('user_id')->relationship('user', 'name')->disabled()->label('Akun Pelapor'),
                    ])->columns(3),
                
                Forms\Components\Section::make('Detail Pengaduan')
                    ->schema([
                        Forms\Components\Textarea::make('isi_pengaduan')->disabled()->columnSpanFull(),
                        Forms\Components\FileUpload::make('bukti_foto')->disabled()->label('Bukti Foto'),
                    ]),

                Forms\Components\Section::make('Tindak Lanjut Admin')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'Diterima' => 'Diterima',
                                'Diproses' => 'Diproses',
                                'Selesai' => 'Selesai',
                            ])
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pelapor')->searchable(),
                Tables\Columns\ImageColumn::make('bukti_foto')
                    ->label('Bukti')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('isi_pengaduan')->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'primary' => 'Diterima',
                    'warning' => 'Diproses',
                    'success' => 'Selesai',
                ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label('Tanggal Masuk'),
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
            'index' => Pages\ListPengaduans::route('/'),
            'create' => Pages\CreatePengaduan::route('/create'),
            'edit' => Pages\EditPengaduan::route('/{record}/edit'),
        ];
    }
}
