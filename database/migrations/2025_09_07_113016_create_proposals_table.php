<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kompleks_id')->constrained('kompleks')->cascadeOnDelete();
            $table->string('nama_pengaju');
            $table->string('kontak_pengaju', 20);
            $table->string('dokumen_proposal');
            $table->text('catatan')->nullable();
            $table->enum('status', ['Diajukan', 'Diverifikasi', 'Disetujui', 'Ditolak'])->default('Diajukan');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('proposals');
    }
};