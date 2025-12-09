<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komplek_barus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Siapa yang mengajukan
            $table->foreignId('kecamatan_id')->constrained('kecamatans');
            $table->foreignId('kelurahan_id')->constrained('kelurahans');
            $table->string('nama_komplek');
            $table->string('alamat');
            $table->string('nomor_hp'); // Request Anda: Tambahan nomor HP
            $table->enum('status', ['Pending', 'Diproses', 'Diterima', 'Ditolak'])->default('Pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komplek_barus');
    }
};