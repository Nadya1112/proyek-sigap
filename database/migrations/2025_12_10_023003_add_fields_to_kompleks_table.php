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
            $table->string('nama_pengembang')->nullable()->after('nama_komplek');
            $table->text('alamat_komplek')->nullable()->after('nama_pengembang');
            $table->integer('jumlah_unit')->nullable()->after('jumlah_sertifikat');
            $table->string('fasilitas_ibadah')->nullable()->after('status_aset');
            $table->string('fasilitas_umum')->nullable()->after('fasilitas_ibadah');
            $table->string('fasilitas_pendidikan')->nullable()->after('fasilitas_umum');
            $table->string('fasilitas_kesehatan')->nullable()->after('fasilitas_pendidikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kompleks', function (Blueprint $table) {
            $table->dropColumn([
                'nama_pengembang',
                'alamat_komplek',
                'jumlah_unit',
                'fasilitas_ibadah',
                'fasilitas_umum',
                'fasilitas_pendidikan',
                'fasilitas_kesehatan',
            ]);
        });
    }
};
