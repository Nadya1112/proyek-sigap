<?php

namespace App\Filament\Resources\KomplekResource\Pages;

use App\Filament\Resources\KomplekResource;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Komplek; // Import model Komplek
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ListKompleks extends ListRecords
{
    protected static string $resource = KomplekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()
                ->label('Import Excel')
                ->uniqueField('nomor')
                ->handleBlankRows(true) // lewati baris kosong
                ->fields([
                    ImportField::make('nomor')->label('Nomor')->required(),
                    ImportField::make('nama_komplek')->label('Nama Komplek Perumahan')->required(),
                    ImportField::make('kelurahan_nama')->label('Kelurahan')->required(),
                    ImportField::make('kecamatan_nama')->label('Kecamatan')->required(),
                    ImportField::make('status_aset')->label('Status Aset')->required(),
                    ImportField::make('jumlah_sertifikat')->label('Jumlah Sertifikat')->rules(['numeric','nullable']),
                    // optional: ImportField::make('alamat'), ImportField::make('foto_komplek')
                ])
                ->mutateBeforeCreate(function(array $row){
                    // Normalisasi status_aset
                    $map = [
                        'sudah penyerahan'   => 'Sudah Diserahkan',
                        'sudah diserahkan'   => 'Sudah Diserahkan',
                        'belum penyerahan'   => 'Belum Diserahkan',
                        'belum diserahkan'   => 'Belum Diserahkan',
                        'proses penyerahan'  => 'Proses Penyerahan',
                        'proses diserahkan'  => 'Proses Penyerahan',
                    ];
                    $key = strtolower(trim((string)($row['status_aset'] ?? '')));
                    $row['status_aset'] = $map[$key] ?? 'Belum Diserahkan';

                    // Jumlah sertifikat -> integer default 0
                    $row['jumlah_sertifikat'] = isset($row['jumlah_sertifikat']) && $row['jumlah_sertifikat'] !== ''
                        ? (int) $row['jumlah_sertifikat']
                        : 0;

                    // Kosongkan string kosong jadi null utk kolom nullable
                    foreach (['alamat','foto_komplek'] as $maybeNull) {
                        if (array_key_exists($maybeNull, $row) && $row[$maybeNull] === '') $row[$maybeNull] = null;
                    }

                    return $row;
                })
                ->handleRecordCreation(function($data) {
                    $kecamatan = \App\Models\Kecamatan::firstOrCreate(['nama_kecamatan' => trim($data['kecamatan_nama'])]);
                    $kelurahan = \App\Models\Kelurahan::firstOrCreate(
                        ['nama_kelurahan' => trim($data['kelurahan_nama']), 'kecamatan_id' => $kecamatan->id],
                    );

                    $data['kelurahan_id'] = $kelurahan->id;

                    unset($data['kelurahan_nama'], $data['kecamatan_nama']);

                    return \App\Models\Komplek::create($data);
                })
        ];
    }
}