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
            $table->string('nama_komplek', 150);
            $table->string('nama_pengembang', 150)->nullable();
            $table->string('foto_komplek', 255)->nullable();
            $table->string('alamat_komplek', 500)->nullable();
            $table->integer('jumlah_sertifikat')->default(0);
            $table->integer('jumlah_unit')->default(0);
            $table->enum('status_aset', ['Sudah Diserahkan','Belum Diserahkan','Proses Penyerahan']);
            $table->text('fasilitas_ibadah')->nullable();
            $table->text('fasilitas_umum')->nullable();
            $table->text('fasilitas_pendidikan')->nullable();
            $table->text('fasilitas_kesehatan')->nullable();
            $table->double('latitude');
            $table->double('longitude');
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kompleks');
    }
};