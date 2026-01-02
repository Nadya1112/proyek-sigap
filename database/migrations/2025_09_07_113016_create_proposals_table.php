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
            $table->string('nama_pengaju', 100);
            $table->string('kontak_pengaju', 20);
            $table->string('alamat', 255)->nullable();
            $table->string('proposal', 255);
            $table->text('catatan')->nullable();
            $table->enum('status', ['Diajukan', 'Diverifikasi JF', 'Disetujui Kabid', 'Disetujui Kadis', 'Ditolak'])->default('Diajukan');
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('proposals');
    }
};