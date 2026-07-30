<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'sangat_terlambat' to the status_kehadiran enum
        DB::statement("ALTER TABLE absensi MODIFY COLUMN status_kehadiran ENUM('hadir','terlambat','sangat_terlambat','izin','sakit','alpa') NOT NULL DEFAULT 'alpa'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE absensi MODIFY COLUMN status_kehadiran ENUM('hadir','terlambat','izin','sakit','alpa') NOT NULL DEFAULT 'alpa'");
    }
};
