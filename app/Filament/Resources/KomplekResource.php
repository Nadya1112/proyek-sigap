<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KomplekResource\Pages;
use App\Models\Komplek;
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
use App\Imports\KomplekImport;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class KomplekResource extends Resource
{
    protected static ?string $model = Komplek::class;
    protected static ?string $navigationGroup = 'Informasi FASUM';
    protected static ?string $title = 'Komplek';
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    
    // Label untuk tombol create
    protected static ?string $modelLabel = 'Komplek Perumahan';
    protected static ?string $pluralModelLabel = 'Komplek Perumahan';
    
    public static function getCreateButtonLabel(): string
    {
        return 'Buat Komplek Perumahan';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama_komplek')->required(),
            Forms\Components\TextInput::make('nama_pengembang'),
            Forms\Components\Textarea::make('alamat_komplek')->columnSpanFull(),
            Forms\Components\Select::make('kelurahan_id')->relationship('kelurahan', 'nama_kelurahan')->searchable()->preload()->required(),
            Forms\Components\FileUpload::make('foto_komplek')->image()->directory('foto-komplek'),
            Forms\Components\TextInput::make('jumlah_sertifikat')->default('-'),
            Forms\Components\TextInput::make('jumlah_unit')->default('-'),
            Forms\Components\Select::make('status_aset')
                ->options([
                    'Sudah Diserahkan' => 'Sudah Diserahkan',
                    'Belum Diserahkan' => 'Belum Diserahkan',
                    'Proses Penyerahan' => 'Proses Penyerahan',
                ])->required(),
            Forms\Components\TextInput::make('fasilitas_ibadah'),
            Forms\Components\TextInput::make('fasilitas_umum'),
            Forms\Components\TextInput::make('fasilitas_pendidikan'),
            Forms\Components\TextInput::make('fasilitas_kesehatan'),
            Forms\Components\TextInput::make('latitude'),
            Forms\Components\TextInput::make('longitude'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto_komplek')->label('Foto')->circular(),
                Tables\Columns\TextColumn::make('nama_komplek')->label('Nama Komplek')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama_pengembang')->label('Pengembang')->searchable(),
                Tables\Columns\TextColumn::make('alamat_komplek')->label('Alamat')->searchable(),
                Tables\Columns\TextColumn::make('kelurahan.nama_kelurahan')->label('Kelurahan')->sortable(),
                Tables\Columns\TextColumn::make('kelurahan.kecamatan.nama_kecamatan')->label('Kecamatan')->sortable(),
                Tables\Columns\TextColumn::make('jumlah_sertifikat')->label('Jumlah Sertifikat')->sortable(),
                Tables\Columns\TextColumn::make('jumlah_unit')->label('Jumlah Unit')->sortable(),
                Tables\Columns\TextColumn::make('status_aset')->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Sudah Diserahkan' => 'success',
                    'Belum Diserahkan' => 'danger',
                    'Proses Penyerahan' => 'warning',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('fasilitas_ibadah')->label('Fasilitas Ibadah'),
                Tables\Columns\TextColumn::make('fasilitas_umum')->label('Fasilitas Umum'),
                Tables\Columns\TextColumn::make('fasilitas_pendidikan')->label('Fasilitas Pendidikan'),
                Tables\Columns\TextColumn::make('fasilitas_kesehatan')->label('Fasilitas Kesehatan'),
                Tables\Columns\TextColumn::make('latitude')->label('Latitude'),
                Tables\Columns\TextColumn::make('longitude')->label('Longitude'),
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
                        ->helperText('Pastikan ada kolom: nama_komplek, nama_kelurahan, status_aset, dll.')
                        ->disk('public') // Pastikan disk public
                        ->directory('imports')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $filePath = Storage::disk('public')->path($data['file_excel']);
                        $import = new KomplekImport;
                        Excel::import($import, $filePath);
                        
                        $importedRowCount = $import->getImportedRowCount();

                        if ($importedRowCount > 0) {
                            Notification::make()
                                ->title("Sukses: {$importedRowCount} Data Komplek Berhasil Diimpor")
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

    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKompleks::route('/'),
            'create' => Pages\CreateKomplek::route('/create'),
            'edit' => Pages\EditKomplek::route('/{record}/edit'),
        ];
    }
    public static function canViewAny(): bool { return in_array(auth()->user()->role, ['admin','Staff','JF PSU','Kabid','Kadis']); }
    public static function canCreate(): bool { return Auth::user()->isSuperAdmin(); }
    public static function canEdit(Model $record): bool { return Auth::user()->isSuperAdmin(); }
    public static function canDelete(Model $record): bool { return Auth::user()->isSuperAdmin(); }
    public static function canDeleteAny(): bool { return Auth::user()->isSuperAdmin(); }
}