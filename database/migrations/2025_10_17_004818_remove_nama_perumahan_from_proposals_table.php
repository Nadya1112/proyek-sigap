<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Hapus kolom 'nama_perumahan'
            $table->dropColumn('nama_perumahan');
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Sediakan cara untuk mengembalikannya jika migrasi di-rollback
            $table->string('nama_perumahan')->after('kontak_pengaju');
        });
    }
};