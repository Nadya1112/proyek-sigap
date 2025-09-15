<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        Document::insert([
            [
                'title'    => 'UUD No 1 Tahun 2011',
                'slug'     => 'uud-no-1-tahun-2011',
                'filename' => 'uud-no-1-2011.pdf',
                'mime'     => 'application/pdf',
                'size_kb'  => 524,
                'year'     => 2011,
                'created_at'=>now(), 'updated_at'=>now(),
            ],
            [
                'title'    => 'Perda No 1 Tahun 2023',
                'slug'     => 'perda-no-1-tahun-2023',
                'filename' => 'perda-no-1-2023.pdf',
                'mime'     => 'application/pdf',
                'size_kb'  => 812,
                'year'     => 2023,
                'created_at'=>now(), 'updated_at'=>now(),
            ],
            // … tambah baris lain
        ]);
    }
}