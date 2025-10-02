<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Method ini akan dieksekusi saat Anda menjalankan `php artisan migrate`.
     */
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // 1. Tambahkan kolom 'nama_perumahan' setelah 'kontak_pengaju'
            $table->string('nama_perumahan')->after('kontak_pengaju');

            // 2. Tambahkan kolom 'alamat' setelah 'nama_perumahan'
            $table->string('alamat')->after('nama_perumahan');
            
            // 3. Ubah nama kolom 'dokumen_proposal' menjadi 'proposal'
            $table->renameColumn('dokumen_proposal', 'proposal');
        });
    }

    /**
     * Reverse the migrations.
     * Method ini akan dieksekusi jika Anda menjalankan `php artisan migrate:rollback`.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // 1. Kembalikan nama kolom 'proposal' menjadi 'dokumen_proposal'
            $table->renameColumn('proposal', 'dokumen_proposal');
            
            // 2. Hapus kedua kolom yang tadi ditambahkan
            $table->dropColumn(['alamat', 'nama_perumahan']);
        });
    }
};