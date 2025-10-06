<?php
namespace App\Filament\Resources\DokumenRegulasiResource\Pages;
use App\Filament\Resources\DokumenRegulasiResource;
use App\Filament\Resources\DokumenRegulasi; // Import model "palsu"
use App\Services\GoogleDriveService;
use Filament\Resources\Pages\ListRecords;

class ListDokumenRegulasis extends ListRecords {
    protected static string $resource = DokumenRegulasiResource::class;

    // Alih-alih getTableRecords(), kita akan menggunakan getTableQuery()
    // Ini adalah cara yang benar di Filament v3 untuk data non-database
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Ambil data dari Google Drive
        $driveFiles = resolve(GoogleDriveService::class)->listFiles();
        
        // Ubah array menjadi koleksi Model
        $records = DokumenRegulasi::hydrate($driveFiles);

        // Buat query "palsu" dari koleksi tersebut
        return $records->toQuery();
    }
}