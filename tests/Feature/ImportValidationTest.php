<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kecamatan;
use App\Imports\KecamatanImport;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Buat user untuk otentikasi
        $this->actingAs(User::factory()->create());
    }

    /** @test */
    public function it_successfully_imports_valid_kecamatan_data()
    {
        Excel::fake();

        $file = UploadedFile::fake()->createWithContent(
            'kecamatan.xlsx',
            "nama_kecamatan\nKecamatan Baru"
        );

        Excel::import(new KecamatanImport, $file);

        Excel::assertImported('kecamatan.xlsx');

        $this->assertDatabaseHas('kecamatans', [
            'nama_kecamatan' => 'Kecamatan Baru'
        ]);
    }

    /** @test */
    public function it_rejects_import_when_required_headers_are_missing()
    {
        Excel::fake();

        // File ini sengaja dibuat salah (menggunakan `nama_kelurahan` bukan `nama_kecamatan`)
        $file = UploadedFile::fake()->createWithContent(
            'kecamatan_salah.xlsx',
            "nama_kelurahan\nKelurahan Salah"
        );

        try {
            Excel::import(new KecamatanImport, $file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $this->assertCount(1, $failures);
            $this->assertEquals('Kolom `nama_kecamatan` wajib diisi.', $failures[0]->errors()[0]);
        }

        Excel::assertNotImported('kecamatan_salah.xlsx');
    }

    /** @test */
    public function it_rejects_import_with_prohibited_columns()
    {
        Excel::fake();

        // File ini sengaja berisi kolom yang tidak seharusnya ada
        $file = UploadedFile::fake()->createWithContent(
            'kecamatan_aneh.xlsx',
            "nama_kecamatan,nama_komplek\nKecamatan Aneh,Komplek Aneh"
        );

        try {
            Excel::import(new KecamatanImport, $file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $this->assertCount(1, $failures);
            // Cek pesan error untuk kolom yang dilarang
            $this->assertEquals('File salah! Kolom `nama_komplek` seharusnya tidak ada di file Kecamatan.', $failures[0]->errors()[0]);
        }
         Excel::assertNotImported('kecamatan_aneh.xlsx');
    }
}