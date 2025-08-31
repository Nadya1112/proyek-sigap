<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fasum;

class FasumSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_perumahan'=>'Griya Maju','alamat'=>'Jl. Maju No.1','status_sertifikat'=>'sudah','tanggal_serah'=>'2024-01-12'],
            ['nama_perumahan'=>'Permata Indah','alamat'=>'Jl. Indah No.2','status_sertifikat'=>'belum','keterangan'=>'Proses verifikasi'],
            // tambah sesuai kebutuhan
        ];
        foreach($data as $d) Fasum::create($d);
    }
}
