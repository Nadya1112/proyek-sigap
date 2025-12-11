<?php

namespace App\Imports;

use App\Models\Kecamatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

use Illuminate\Validation\ValidationException;

class KecamatanImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithEvents
{
    private int $importedRowCount = 0;

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $headings = array_map('trim', $event->sheet->getDelegate()->toArray()[0]);

                if (
                    !in_array('nama_kecamatan', $headings) ||
                    in_array('nama_kelurahan', $headings) ||
                    in_array('nama_komplek', $headings)
                ) {
                    throw ValidationException::withMessages([
                        'file' => 'File yang diunggah tidak sesuai. Pastikan Anda mengunggah file Excel untuk Kecamatan.'
                    ]);
                }
            },
        ];
    }

    public function rules(): array
    {
        return [
            'nama_kecamatan' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_kecamatan.required' => 'Kolom `nama_kecamatan` wajib diisi.',
        ];
    }

    public function model(array $row)
    {
        // Lewati baris jika `nama_kecamatan` kosong
        if (empty($row['nama_kecamatan'])) {
            return null;
        }

        $kecamatan = Kecamatan::updateOrCreate(
            ['nama_kecamatan' => $row['nama_kecamatan']],
            [
                'warna'    => $row['warna'] ?? '#000000',
                'geometri' => $row['geometri'] ?? '{}',
            ]
        );

        if ($kecamatan) {
            $this->importedRowCount++;
        }

        return $kecamatan;
    }

    public function onFailure(Failure ...$failures)
    {
        // Biarkan kosong untuk melewati baris yang gagal validasi secara diam-diam
    }

    public function getImportedRowCount(): int
    {
        return $this->importedRowCount;
    }
}