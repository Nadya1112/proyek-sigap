<?php

namespace App\Imports;

use App\Models\Komplek;
use App\Models\Kelurahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

use Illuminate\Validation\ValidationException;

class KomplekImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithEvents
{
    private int $importedRowCount = 0;

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $headings = array_map('trim', $event->sheet->getDelegate()->toArray()[0]);

                if (
                    !in_array('nama_komplek', $headings) ||
                    !in_array('nama_kelurahan', $headings) ||
                    !in_array('latitude', $headings) ||
                    !in_array('longitude', $headings) ||
                    in_array('nama_kecamatan', $headings)
                ) {
                    throw ValidationException::withMessages([
                        'file' => 'File yang diunggah tidak sesuai. Pastikan Anda mengunggah file Excel untuk Komplek dan tidak ada kolom "nama_kecamatan".'
                    ]);
                }
            },
        ];
    }

    public function rules(): array
    {
        return [
            'nama_komplek'   => 'required|string',
            'nama_kelurahan' => 'required|string|exists:kelurahans,nama_kelurahan',
            'latitude'       => 'required|numeric',
            'longitude'      => 'required|numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_komplek.required'   => 'Kolom `nama_komplek` wajib diisi.',
            'nama_kelurahan.required' => 'Kolom `nama_kelurahan` wajib diisi.',
            'nama_kelurahan.exists'   => 'Kelurahan dengan nama `:input` tidak ditemukan di database.',
            'latitude.required'       => 'Kolom `latitude` wajib diisi.',
            'latitude.numeric'        => 'Kolom `latitude` harus berupa angka.',
            'longitude.required'      => 'Kolom `longitude` wajib diisi.',
            'longitude.numeric'       => 'Kolom `longitude` harus berupa angka.',
        ];
    }

    public function model(array $row)
    {
        // Lewati baris jika data utama tidak ada
        if (empty($row['nama_komplek']) || empty($row['nama_kelurahan'])) {
            return null;
        }

        $namaKelurahanExcel = trim($row['nama_kelurahan']);
        $kelurahan = Kelurahan::where('nama_kelurahan', 'LIKE', '%' . $namaKelurahanExcel . '%')->first();

        // Seharusnya validasi `exists` sudah menangani ini, tapi sebagai pengaman tambahan
        if (!$kelurahan) {
            return null;
        }

        $komplek = Komplek::updateOrCreate(
            ['nama_komplek' => $row['nama_komplek']],
            [
                'kelurahan_id'        => $kelurahan->id,
                'nama_pengembang'     => $row['nama_pengembang'] ?? null,
                'alamat_komplek'      => $row['alamat_komplek'] ?? null,
                'jumlah_sertifikat'   => is_numeric($row['jumlah_sertifikat'] ?? null) ? $row['jumlah_sertifikat'] : 0,
                'jumlah_unit'         => is_numeric($row['jumlah_unit'] ?? null) ? $row['jumlah_unit'] : 0,
                'status_aset'         => $row['status_aset'] ?? 'Belum Diserahkan',
                'fasilitas_ibadah'    => $row['fasilitas_ibadah'] ?? null,
                'fasilitas_umum'      => $row['fasilitas_umum'] ?? null,
                'fasilitas_pendidikan'=> $row['fasilitas_pendidikan'] ?? null,
                'fasilitas_kesehatan' => $row['fasilitas_kesehatan'] ?? null,
                'latitude'            => isset($row['latitude']) && is_numeric($row['latitude']) ? (float) $row['latitude'] / 100000000 : null,
                'longitude'           => isset($row['longitude']) && is_numeric($row['longitude']) ? (float) $row['longitude'] / 100000000 : null,
            ]
        );

        if ($komplek) {
            $this->importedRowCount++;
        }

        return $komplek;
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