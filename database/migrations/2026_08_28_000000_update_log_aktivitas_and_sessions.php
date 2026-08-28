<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom hash ke log_aktivitas
        if (Schema::hasTable('log_aktivitas')) {
            Schema::table('log_aktivitas', function (Blueprint $table) {
                $table->string('hash', 64)->nullable()->after('user_agent');
            });
        }

        // 2. Buat tabel sessions jika belum ada (karena menggunakan database session driver)
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('log_aktivitas')) {
            Schema::table('log_aktivitas', function (Blueprint $table) {
                $table->dropColumn('hash');
            });
        }
        Schema::dropIfExists('sessions');
    }
};
