<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelurahanResource\Pages;
use App\Models\Kelurahan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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

// Import Tambahan untuk XLSX
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KelurahanImport;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class KelurahanResource extends Resource
{
    protected static ?string $model = Kelurahan::class;
    protected static ?string $navigationGroup = 'Informasi FASUM';
    protected static ?string $title = 'Kelurahan';
    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('kecamatan_id')->relationship('kecamatan', 'nama_kecamatan')->label('Kecamatan')->preload()->required(),
            Forms\Components\TextInput::make('nama_kelurahan')->label('Kelurahan')->required()->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kecamatan.nama_kecamatan')->label('Kecamatan')->sortable(),
                Tables\Columns\TextColumn::make('nama_kelurahan')->label('Kelurahan')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('kompleks_count')->label('Jumlah Komplek')->sortable(),
            ])
            ->filters([TrashedFilter::make()])
            ->headerActions([
                ExportAction::make()->icon('heroicon-o-arrow-up-tray')->visible(fn () => auth()->user()->isSuperAdmin()),
                
                // --- ACTION IMPORT XLSX ---
            Action::make('importExcel')
                ->label('Impor')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->form([
                    FileUpload::make('file_excel')
                        ->label('File Excel (.xlsx)')
                        ->helperText('Pastikan ada kolom: nama_kelurahan, nama_kecamatan')
                        ->disk('public') // Pastikan disk public
                        ->directory('imports')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $filePath = Storage::disk('public')->path($data['file_excel']);
                        $import = new KelurahanImport;
                        Excel::import($import, $filePath);
                        
                        $importedRowCount = $import->getImportedRowCount();

                        if ($importedRowCount > 0) {
                            Notification::make()
                                ->title("Sukses: {$importedRowCount} Data Kelurahan Berhasil Diimpor")
                                ->success()
                                ->send()
                                ->persistent();
                        } else {
                            Notification::make()
                                ->title('Informasi Impor')
                                ->body('Tidak ada data baru yang diimpor. Periksa kembali file Anda apakah sudah sesuai format atau tidak ada data duplikat.')
                                ->warning()
                                ->send()
                                ->persistent();
                        }

                    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                        $failures = $e->failures();
                        $errorMessages = [];
                        foreach ($failures as $failure) {
                            $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
                        }

                        Notification::make()
                            ->title('Gagal Impor: Data Tidak Valid')
                            ->body(implode("\n", $errorMessages) ?: $e->getMessage())
                            ->danger()
                            ->send()
                                ->persistent();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Impor')
                            ->body($e->getMessage())
                            ->danger()
                            ->send()
                                ->persistent();
                    }
                })
                ->visible(fn () => auth()->user()->isSuperAdmin()),
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
                    ExportBulkAction::make()->icon('heroicon-o-arrow-up-tray'),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder { return parent::getEloquentQuery()->withCount('kompleks'); }
    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelurahans::route('/'),
            'create' => Pages\CreateKelurahan::route('/create'),
            'edit' => Pages\EditKelurahan::route('/{record}/edit'),
        ];
    }
    public static function canViewAny(): bool { return in_array(auth()->user()->role, ['admin','Staff','JF PSU','Kabid','Kadis']); }
    public static function canCreate(): bool { return Auth::user()->isSuperAdmin(); }
    public static function canEdit(Model $record): bool { return Auth::user()->isSuperAdmin(); }
    public static function canDelete(Model $record): bool { return Auth::user()->isSuperAdmin(); }
    public static function canDeleteAny(): bool { return Auth::user()->isSuperAdmin(); }
}