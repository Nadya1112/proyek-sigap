<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            WilayahSeeder::class,  // Seeder baru untuk data Kecamatan & Kompleks
            DocumentSeeder::class, // Seeder yang sudah ada
            FasumSeeder::class,    // Seeder yang sudah ada
        ]);
    }
}