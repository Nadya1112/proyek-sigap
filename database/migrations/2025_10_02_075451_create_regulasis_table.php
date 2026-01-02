<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regulasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150);
            $table->integer('tahun')->nullable(); // Menyimpan tahun (misal: 2025)
            $table->string('path', 255); // Untuk menyimpan lokasi file di storage/app/public/regulasi
            $table->string('nama_file_asli', 255);
            $table->string('tipe_file', 50);
            $table->integer('ukuran_file'); // dalam kilobyte (KB)
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulasis');
    }
};