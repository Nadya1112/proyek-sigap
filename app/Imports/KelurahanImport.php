<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

use Illuminate\Validation\ValidationException;

class KelurahanImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithEvents
{
    private int $importedRowCount = 0;

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $headings = array_map('trim', $event->sheet->getDelegate()->toArray()[0]);

                if (
                    !in_array('nama_kelurahan', $headings) ||
                    !in_array('nama_kecamatan', $headings) ||
                    in_array('nama_komplek', $headings)
                ) {
                    throw ValidationException::withMessages([
                        'file' => 'File yang diunggah tidak sesuai. Pastikan Anda mengunggah file Excel untuk Kelurahan.'
                    ]);
                }
            },
        ];
    }

    public function rules(): array
    {
        return [
            'nama_kecamatan' => 'required|string|exists:kecamatans,nama_kecamatan',
            'nama_kelurahan' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_kecamatan.required' => 'Kolom `nama_kecamatan` wajib diisi.',
            'nama_kecamatan.exists'   => 'Kecamatan dengan nama `:input` tidak ditemukan di database.',
            'nama_kelurahan.required' => 'Kolom `nama_kelurahan` wajib diisi.',
        ];
    }

    public function model(array $row)
    {
        // Lewati baris jika data utama tidak ada
        if (empty($row['nama_kelurahan']) || empty($row['nama_kecamatan'])) {
            return null;
        }

        $kecamatan = Kecamatan::where('nama_kecamatan', 'LIKE', '%' . trim($row['nama_kecamatan']) . '%')->first();

        // Seharusnya validasi `exists` sudah menangani ini, tapi sebagai pengaman tambahan
        if (!$kecamatan) {
            return null;
        }

        $kelurahan = Kelurahan::updateOrCreate(
            [
                'nama_kelurahan' => $row['nama_kelurahan'],
                'kecamatan_id'   => $kecamatan->id,
            ],
            [] // Tidak ada field lain untuk di-update saat ini
        );

        if ($kelurahan) {
            $this->importedRowCount++;
        }

        return $kelurahan;
    }

    public function onFailure(Failure ...$failures)
    {
        // Biarkan kosong untuk melewati baris yang gagal
    }

    public function getImportedRowCount(): int
    {
        return $this->importedRowCount;
    }
}