<?php

namespace App\Filament\Pages;

use App\Services\GoogleDriveService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Livewire\WithFileUploads;

class ManageDokumenInformasi extends Page
{
    use WithFileUploads;

    protected static ?string $navigationLabel = 'Kelola Dokumen';
    protected static ?string $title = 'Kelola Dokumen Informasi';
    protected static string $view = 'filament.pages.manage-dokumen-informasi';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 1;

    /** @var array<array{id:string,title:string,year:?int,ext:string,size:?int,modified:?string}> */
    public array $docs = [];

    // Create modal
    public bool $createOpen = false;
    public string $createTitle = '';
    public ?int $createYear = null;
    public $createFile;

    // Edit modal
    public bool $editOpen = false;
    public string $editId = '';
    public string $editTitle = '';
    public ?int $editYear = null;

    // Replace modal
    public bool $replaceOpen = false;
    public string $replaceId = '';
    public string $replaceTitle = '';
    public ?int $replaceYear = null;
    public $replaceFile;

    public function mount(GoogleDriveService $drive)
    {
        $this->refreshDocs($drive);
    }

    public function refreshDocs(GoogleDriveService $drive): void
    {
        $this->docs = $drive->listFiles();
    }

    /** --------- CREATE --------- */
    public function openCreate(): void
    {
        $this->resetValidation();
        $this->createOpen = true;
        $this->createTitle = '';
        $this->createYear  = null;
        $this->createFile  = null;
    }

    public function submitCreate(GoogleDriveService $drive): void
    {
        $this->validate([
            'createTitle' => ['required','string','max:255'],
            'createFile'  => ['required','file','max:20480','mimes:pdf,doc,docx'],
            'createYear'  => ['nullable','integer','digits:4'],
        ]);

        $drive->upload($this->createFile, $this->createTitle, $this->createYear);

        $this->createOpen = false;
        $this->refreshDocs($drive);

        Notification::make()->title('Dokumen berhasil diunggah.')->success()->send();
    }

    /** --------- EDIT (rename) --------- */
    public function openEdit(string $id): void
    {
        $this->resetValidation();
        $doc = collect($this->docs)->firstWhere('id', $id);
        if (!$doc) return;

        $this->editId    = $id;
        $this->editTitle = $doc['title'];
        $this->editYear  = $doc['year'];
        $this->editOpen  = true;
    }

    public function submitEdit(GoogleDriveService $drive): void
    {
        $this->validate([
            'editTitle' => ['required','string','max:255'],
            'editYear'  => ['nullable','integer','digits:4'],
        ]);

        $drive->rename($this->editId, $this->editTitle, $this->editYear);

        $this->editOpen = false;
        $this->refreshDocs($drive);
        Notification::make()->title('Dokumen diperbarui.')->success()->send();
    }

    /** --------- REPLACE --------- */
    public function openReplace(string $id): void
    {
        $this->resetValidation();
        $doc = collect($this->docs)->firstWhere('id', $id);
        if (!$doc) return;

        $this->replaceId    = $id;
        $this->replaceTitle = $doc['title'];
        $this->replaceYear  = $doc['year'];
        $this->replaceFile  = null;
        $this->replaceOpen  = true;
    }

    public function submitReplace(GoogleDriveService $drive): void
    {
        $this->validate([
            'replaceFile'  => ['required','file','max:20480','mimes:pdf,doc,docx'],
            'replaceTitle' => ['nullable','string','max:255'],
            'replaceYear'  => ['nullable','integer','digits:4'],
        ]);

        $drive->replace($this->replaceId, $this->replaceFile, $this->replaceTitle ?: null, $this->replaceYear);

        $this->replaceOpen = false;
        $this->refreshDocs($drive);
        Notification::make()->title('File berhasil diganti.')->success()->send();
    }

    /** --------- DELETE --------- */
    public function delete(string $id, GoogleDriveService $drive): void
    {
        $drive->delete($id);
        $this->refreshDocs($drive);
        Notification::make()->title('Dokumen dihapus.')->success()->send();
    }

    /** --------- DOWNLOAD (buka tab) --------- */
    public function download(string $id, GoogleDriveService $drive)
    {
        return redirect()->away($drive->exportDownloadUrl($id));
    }
}
