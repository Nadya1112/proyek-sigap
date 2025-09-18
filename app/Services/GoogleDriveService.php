<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class GoogleDriveService
{
    protected GoogleDrive $drive;
    protected string $folderId;

    public function __construct()
    {
        // Ambil path JSON & folder id dari config
        $jsonPath = config('services.gdrive.service_account_json');
        $folderId = config('services.gdrive.folder_id');

        // Validasi konfigurasi agar error-nya eksplisit
        if (!$jsonPath) {
            throw new \RuntimeException("Config 'services.gdrive.service_account_json' kosong.");
        }
        if (!file_exists($jsonPath)) {
            throw new \RuntimeException("File service account JSON tidak ditemukan: {$jsonPath}");
        }
        if (!$folderId) {
            throw new \RuntimeException("Config 'services.gdrive.folder_id' kosong.");
        }

        // Normalisasi folderId: buang querystring/URL penuh jika user salah paste
        $folderId = $this->normalizeFolderId($folderId);

        $client = new GoogleClient();
        // Untuk Service Account: berikan path file JSON
        $client->setAuthConfig($jsonPath);
        $client->setApplicationName('SIGAP Drive');
        $client->setScopes([GoogleDrive::DRIVE]);

        $this->drive = new GoogleDrive($client);
        $this->folderId = $folderId;
    }

    private function normalizeFolderId(string $raw): string
    {
        // Contoh URL: https://drive.google.com/drive/folders/ABCD1234?hl=ID
        // Ambil segmen setelah "/folders/" jika berupa URL,
        // lalu hilangkan query-string (?xxx)
        if (str_contains($raw, '/folders/')) {
            $raw = substr($raw, strrpos($raw, '/folders/') + 9);
        }
        if (($q = strpos($raw, '?')) !== false) {
            $raw = substr($raw, 0, $q);
        }
        // Trim spasi
        return trim($raw);
    }

    /** List file di folder */
    public function listFiles(): array
    {
        $q = sprintf("'%s' in parents and trashed=false", $this->folderId);
        $files = $this->drive->files->listFiles([
            'q' => $q,
            'fields' => 'files(id, name, mimeType, size, modifiedTime)',
            'orderBy' => 'modifiedTime desc',
            // Jika memakai Shared Drive, aktifkan dua baris ini:
            // 'supportsAllDrives' => true,
            // 'includeItemsFromAllDrives' => true,
        ])->files;

                    $out = [];
            foreach ($files as $f) {
                $name  = $f->name ?? '';
                $ext   = strtoupper(pathinfo($name, PATHINFO_EXTENSION));
                $title = pathinfo($name, PATHINFO_FILENAME);
                $year  = null;

                if ($title && preg_match('/__(\d{4})$/', $title, $m)) {
                    $year  = $m[1];
                    $title = Str::of($title)->replaceLast("__{$year}", '')->value();
                }

                $sizeKb   = isset($f->size) ? (int) $f->size / 1024 : null;      // KB
                $modified = $f->modifiedTime ?? null;

                $out[] = [
                    // kunci “baru” (rapi)
                    'id'       => $f->id,
                    'title'    => $title,
                    'year'     => $year,
                    'ext'      => $ext,
                    'size'     => $sizeKb,
                    'modified' => $modified,
                    'download' => "https://drive.google.com/uc?export=download&id={$f->id}",

                    // kunci “lama” untuk kompatibilitas controller/blade lama
                    'name'     => $title,     // alias dari title
                    'mtime'    => $modified,  // alias dari modified
                ];
            }
            return $out;

    }

    /** Upload baru */
    public function upload(UploadedFile $file, string $title, ?int $year = null): void
    {
        $safe = Str::slug($title, '-');
        $name = $year
            ? "{$safe}__{$year}.{$file->getClientOriginalExtension()}"
            : "{$safe}.{$file->getClientOriginalExtension()}";

        $meta = new GoogleDrive\DriveFile([
            'name'    => $name,
            'parents' => [$this->folderId],
        ]);

        $this->drive->files->create($meta, [
            'data'       => file_get_contents($file->getRealPath()),
            'mimeType'   => $file->getMimeType(),
            'uploadType' => 'multipart',
            // 'supportsAllDrives' => true, // Jika pakai Shared Drive
        ]);
    }

    /** Hapus file */
    public function delete(string $fileId): void
    {
        $this->drive->files->delete($fileId/*, ['supportsAllDrives' => true]*/);
    }

    /** Rename (edit judul/tahun) */
    public function rename(string $fileId, string $newTitle, ?int $newYear = null): void
    {
        $file = $this->drive->files->get($fileId, ['fields' => 'name']);
        $ext  = pathinfo($file->name, PATHINFO_EXTENSION);

        $safe    = Str::slug($newTitle, '-');
        $newName = $newYear ? "{$safe}__{$newYear}.{$ext}" : "{$safe}.{$ext}";

        $meta = new GoogleDrive\DriveFile(['name' => $newName]);
        $this->drive->files->update($fileId, $meta, [
            'fields' => 'id,name',
            // 'supportsAllDrives' => true,
        ]);
    }

    /** Replace file (ganti file fisik) */
    public function replace(string $fileId, UploadedFile $newFile, ?string $newTitle = null, ?int $newYear = null): void
    {
        $old = $this->drive->files->get($fileId, ['fields' => 'name']);
        $ext = pathinfo($newFile->getClientOriginalExtension(), PATHINFO_EXTENSION);

        if ($newTitle !== null) {
            $safe = Str::slug($newTitle, '-');
            $name = $newYear ? "{$safe}__{$newYear}.{$ext}" : "{$safe}.{$ext}";
        } else {
            $base = pathinfo($old->name, PATHINFO_FILENAME);
            $name = "{$base}.{$ext}";
        }

        $meta = new GoogleDrive\DriveFile(['name' => $name]);
        $this->drive->files->update($fileId, $meta, [
            'data'       => file_get_contents($newFile->getRealPath()),
            'mimeType'   => $newFile->getMimeType(),
            'uploadType' => 'multipart',
            'fields'     => 'id,name',
            // 'supportsAllDrives' => true,
        ]);
    }

    /** Link download file */
    public function exportDownloadUrl(string $fileId): string
    {
        return "https://drive.google.com/uc?export=download&id={$fileId}";
    }
}
