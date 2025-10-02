<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            // Menambahkan kolom baru untuk data poligon batas wilayah
            $table->multiPolygon('batas_wilayah')->nullable()->after('warna');
            
            // Menambahkan index spasial untuk performa query
            $table->spatialIndex('batas_wilayah');
        });
    }

    public function down(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            $table->dropSpatialIndex(['batas_wilayah']);
            $table->dropColumn('batas_wilayah');
        });
    }
};