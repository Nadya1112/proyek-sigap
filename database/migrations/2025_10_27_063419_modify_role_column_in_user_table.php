<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Ubah ENUM untuk menyertakan role baru
            $table->enum('role', ['admin', 'pengguna', 'Staff', 'JF PSU', 'Kabid', 'Kadis'])
                  ->default('pengguna')->change(); // Pertahankan default 'pengguna'
        });
        // Update role 'admin' lama ke salah satu role baru (misal: Kadis)
        DB::table('users')->where('role', 'admin')->update(['role' => 'Kadis']);
    }
    public function down(): void {
        // Logika rollback (kembalikan ke enum lama, mungkin perlu penyesuaian data)
         DB::table('users')->whereIn('role', ['Staff', 'JF PSU', 'Kabid', 'Kadis'])->update(['role' => 'admin']);
         Schema::table('users', function (Blueprint $table) {
             $table->enum('role', ['admin', 'pengguna'])->default('pengguna')->change();
         });
    }
};