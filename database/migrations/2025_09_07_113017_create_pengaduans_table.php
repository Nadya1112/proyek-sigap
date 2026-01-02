<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_pelapor', 100);
            $table->string('kontak_pelapor', 20);
            $table->string('judul_pengaduan', 150);
            $table->text('isi_pengaduan');
            $table->string('bukti_foto', 255);
            $table->enum('status', ['Diajukan', 'Diverifikasi JF', 'Disetujui Kabid', 'Disetujui Kadis', 'Ditolak'])->default('Diajukan');
            $table->text('catatan_admin')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pengaduans');
    }
};