<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // 4–6 digit code + masa berlaku
            $table->string('verification_code', 6)->nullable()->after('password');
            $table->timestamp('verification_expires_at')->nullable()->after('verification_code');

            // pastikan kolom ini boleh null (sudah ada di skema awal Anda)
            $table->timestamp('email_verified_at')->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['verification_code', 'verification_expires_at']);
        });
    }
};
