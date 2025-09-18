<?php

namespace App\Filament\Pages;

use App\Services\GoogleDriveService;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class UploadDokumenInformasi extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationLabel = 'Upload Dokumen Informasi';
    protected static ?string $title = 'Upload Dokumen Informasi';
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.upload-dokumen-informasi';

    public ?string $titleDoc = null;
    public ?string $year = null;
    public $file = null;

    public static function shouldRegisterNavigation(): bool
    {
        // tampilkan hanya untuk admin (role di model User Anda sudah ada)
        $user = auth()->user();
        return $user && ($user->role === 'admin');
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('titleDoc')
                ->label('Judul Dokumen')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('year')
                ->label('Tahun (opsional)')
                ->numeric()
                ->length(4)
                ->nullable(),

            Forms\Components\FileUpload::make('file')
                ->label('File (PDF/DOC/DOCX, maks 20MB)')
                ->required()
                ->acceptedFileTypes(['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->maxSize(20480) // KB
                ->directory('tmp') // hanya sementara (Filament akan taruh di storage/tmp), nanti kita ambil realPath
                ->preserveFilenames()
                ->downloadable(false),
        ];
    }

    public function submit(): void
    {
        $this->validate([
            'titleDoc' => ['required','string','max:255'],
            'year'     => ['nullable','digits:4'],
            'file'     => ['required'],
        ]);

        $uploaded = $this->file;

        // Ambil path asli file yang diupload via Filament
        $storagePath = storage_path('app/'.$uploaded); // karena ->directory('tmp')
        if (! file_exists($storagePath)) {
            Notification::make()
                ->title('Gagal')
                ->body('File sementara tidak ditemukan.')
                ->danger()
                ->send();
            return;
        }

        // Susun nama file rapi untuk publik
        $base = Str::slug($this->titleDoc);
        if (!empty($this->year)) {
            $base .= '-'.$this->year;
        }
        $ext  = pathinfo($storagePath, PATHINFO_EXTENSION);
        $name = $base . ($ext ? ".{$ext}" : '');

        // Bungkus ke UploadedFile agar service bisa menerimanya
        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $storagePath,
            $name,
            \Illuminate\Support\Facades\File::mimeType($storagePath) ?: 'application/octet-stream',
            null,
            true // test mode
        );

        // Upload ke Google Drive
        try {
            app(GoogleDriveService::class)->upload(env('GOOGLE_DRIVE_FOLDER_ID'), $uploadedFile, $name);

            // Bersihkan file tmp
            @unlink($storagePath);

            Notification::make()
                ->title('Berhasil')
                ->body('Dokumen berhasil diunggah ke Google Drive.')
                ->success()
                ->send();

            // reset form
            $this->form->fill([
                'titleDoc' => null,
                'year' => null,
                'file' => null,
            ]);

        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal mengunggah')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
