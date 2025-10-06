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
            $table->string('judul');
            $table->integer('tahun')->nullable();
            $table->string('path'); // Untuk menyimpan lokasi file di storage/app/public/regulasi
            $table->string('nama_file_asli');
            $table->string('tipe_file');
            $table->unsignedInteger('ukuran_file'); // dalam kilobyte (KB)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulasis');
    }
};