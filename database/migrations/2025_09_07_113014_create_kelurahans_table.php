<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('kelurahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->cascadeOnDelete();
            $table->string('nama_kelurahan');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kelurahans');
    }
};