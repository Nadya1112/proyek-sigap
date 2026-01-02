<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom nama_perumahan tidak lagi ditambahkan, jadi tidak perlu dihapus
    }

    public function down(): void
    {
        //
    }
};