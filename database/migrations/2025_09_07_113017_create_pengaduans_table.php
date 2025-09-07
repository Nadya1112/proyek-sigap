<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_pelapor');
            $table->string('kontak_pelapor', 20);
            $table->text('isi_pengaduan');
            $table->string('bukti_foto');
            $table->enum('status', ['Diterima', 'Diproses', 'Selesai'])->default('Diterima');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pengaduans');
    }
};