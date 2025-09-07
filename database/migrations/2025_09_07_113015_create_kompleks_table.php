<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kompleks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelurahan_id')->constrained('kelurahans')->cascadeOnDelete();
            $table->string('nama_komplek');
            $table->text('alamat');
            $table->string('foto_komplek')->nullable();
            $table->integer('jumlah_sertifikat')->default(0);
            $table->string('status_aset');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kompleks');
    }
};