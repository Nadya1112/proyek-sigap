<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- Jangan lupa import DB

return new class extends Migration {
    public function up(): void {
        Schema::table('proposals', function (Blueprint $table) {
            // Ubah ENUM status proposal
            $table->enum('status', ['Diajukan', 'Diverifikasi JF', 'Disetujui Kabid', 'Disetujui Kadis', 'Ditolak'])
                  ->default('Diajukan')->change();
        });
        // Mapping status lama ke baru (opsional, sesuaikan jika perlu)
        DB::table('proposals')->where('status', 'Diverifikasi')->update(['status' => 'Diverifikasi JF']);
        // Status 'Disetujui' lama mungkin perlu ditinjau ulang manual atau dianggap 'Disetujui Kadis'
        DB::table('proposals')->where('status', 'Disetujui')->update(['status' => 'Disetujui Kadis']);
    }
    public function down(): void {
        // Logika rollback
         DB::table('proposals')->where('status', 'Diverifikasi JF')->update(['status' => 'Diverifikasi']);
         DB::table('proposals')->whereIn('status', ['Disetujui Kabid', 'Disetujui Kadis'])->update(['status' => 'Disetujui']);
         Schema::table('proposals', function (Blueprint $table) {
             $table->enum('status', ['Diajukan', 'Diverifikasi', 'Disetujui', 'Ditolak'])
                   ->default('Diajukan')->change();
         });
    }
};