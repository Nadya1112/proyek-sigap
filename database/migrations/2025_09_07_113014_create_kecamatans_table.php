<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kecamatan', 100)->unique();
            $table->string('warna', 10)->nullable();
            $table->json('geometri')->nullable(); // Kolom untuk menyimpan data GeoJSON polygon
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('kecamatans');
    }
};