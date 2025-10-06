<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kompleks', function (Blueprint $table) {
            // 1. Gunakan tipe data spesifik: multiPolygon
            // 2. Gunakan nama yang lebih deskriptif: 'area'
            $table->multiPolygon('area')->nullable()->after('status_aset');

            // 3. Tambahkan index spasial untuk performa query yang SANGAT CEPAT
            $table->spatialIndex('area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kompleks', function (Blueprint $table) {
            // Saat menghapus kolom, hapus index-nya terlebih dahulu
            $table->dropSpatialIndex(['area']);
            $table->dropColumn('area');
        });
    }
};