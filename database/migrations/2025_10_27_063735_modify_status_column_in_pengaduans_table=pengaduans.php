<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('pengaduans', function (Blueprint $table) {
            // Ubah ENUM status pengaduan
            $table->enum('status', ['Diterima', 'Diverifikasi JF', 'Diproses', 'Selesai', 'Ditolak'])
                  ->default('Diterima')->change();
        });
         // Mapping status lama ke baru (opsional)
         DB::table('pengaduans')->where('status', 'Diproses')->update(['status' => 'Diproses']); // Contoh jika nama sama
    }
    public function down(): void {
        // Logika rollback
         DB::table('pengaduans')->where('status', 'Diverifikasi JF')->update(['status' => 'Diterima']); // Contoh mapping rollback
         Schema::table('pengaduans', function (Blueprint $table) {
             $table->enum('status', ['Diterima', 'Diproses', 'Selesai'])
                   ->default('Diterima')->change();
         });
    }
};