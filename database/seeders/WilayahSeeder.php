<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Panggil semua model dan class yang kita butuhkan
use App\Models\Kecamatan;
use App\Models\Kompleks;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bagian ini tetap sama untuk membersihkan tabel sebelum diisi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Kompleks::query()->truncate();
        Kecamatan::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // --- BAGIAN 1: MEMASUKKAN DATA KECAMATAN DARI FILE JSON ---
        // Kode ini akan tetap dijalankan

        $jsonFile = public_path('data/kecamatan.json');

        if (file_exists($jsonFile)) {
            $jsonString = file_get_contents($jsonFile);
            $data = json_decode($jsonString, true);

            foreach ($data['features'] as $fitur) {
                $properties = $fitur['properties'];
                $geometry = $fitur['geometry'];

                $warna = '#cccccc';
                $namaKecamatan = $properties['kecamatan'] ?? 'Nama Tidak Ditemukan';

                if ($namaKecamatan == 'Banjarmasin Selatan') $warna = '#E31A1C'; // Merah
                if ($namaKecamatan == 'Banjarmasin Timur') $warna = '#FF7F00'; // Oranye
                if ($namaKecamatan == 'Banjarmasin Tengah') $warna = '#33A02C'; // Hijau
                if ($namaKecamatan == 'Banjarmasin Barat') $warna = '#FDBF6F'; // Kuning muda
                if ($namaKecamatan == 'Banjarmasin Utara') $warna = '#6A3D9A'; // Ungu

                Kecamatan::create([
                    'nama_kecamatan' => $namaKecamatan,
                    'warna' => $warna,
                    'batas_wilayah' => MultiPolygon::fromJson(json_encode($geometry)),
                ]);
            }
        } else {
            $this->command->warn("Peringatan: File 'public/data/kecamatan.json' tidak ditemukan. Seeding Kecamatan dilewati.");
        }


        /*
        // --- BAGIAN 2: MEMASUKKAN DATA KOMPLEKS (CONTOH MANUAL) ---
        // BAGIAN INI KITA NONAKTIFKAN SEMENTARA SESUAI PERMINTAAN ANDA

        // Ambil data kecamatan yang sudah ada di database untuk relasi
        $kecamatanSelatan = Kecamatan::where('nama_kecamatan', 'Banjarmasin Selatan')->first();

        if ($kecamatanSelatan) {
             Kompleks::create([
                'nama_komplek' => 'Komplek Tata Banua Indah',
                'alamat' => 'Jl. Gubernur Soebardjo',
                'kelurahan_id' => 1, // Ganti dengan ID kelurahan yang sesuai
                'kecamatan_id' => $kecamatanSelatan->id, // Menggunakan ID dari data di atas
                'status_aset' => 'Sudah Diserahkan',
                'area' => MultiPolygon::fromWkt('MULTIPOLYGON(((114.605 -3.341, 114.605 -3.345, 114.609 -3.345, 114.609 -3.341, 114.605 -3.341)))'),
            ]);
        }
        */
    }
}