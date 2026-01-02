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
        // Kolom sudah disesuaikan di migrasi create_proposals
    }

    /**
     * Reverse the migrations.
     * Method ini akan dieksekusi jika Anda menjalankan `php artisan migrate:rollback`.
     */
    public function down(): void
    {
        //
    }
};