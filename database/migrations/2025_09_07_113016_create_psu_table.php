<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('psu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kompleks_id')->constrained('kompleks')->cascadeOnDelete();
            $table->string('jenis_psu');
            $table->string('foto_psu')->nullable();
            $table->decimal('panjang_jalan', 8, 2)->nullable();
            $table->decimal('lebar_jalan', 8, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('psu');
    }
};