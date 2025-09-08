<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('kompleks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelurahan_id')
            ->constrained('kelurahans')
            ->cascadeOnDelete();
            $table->string('nama_komplek');
            $table->string('alamat')->nullable();
            $table->string('foto_komplek')->nullable();
            $table->unsignedInteger('jumlah_sertifikat')->default(0);
            $table->string('nomor')->unique();
            $table->enum('status_aset', ['Sudah Diserahkan','Belum Diserahkan','Proses Penyerahan']);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kompleks');
    }
};