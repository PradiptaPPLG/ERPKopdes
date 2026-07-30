<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwal_shifts')->nullOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->decimal('latitude_masuk', 10, 8)->nullable();
            $table->decimal('longitude_masuk', 11, 8)->nullable();
            $table->decimal('latitude_pulang', 10, 8)->nullable();
            $table->decimal('longitude_pulang', 11, 8)->nullable();
            $table->string('lokasi_masuk', 255)->nullable();
            $table->string('lokasi_pulang', 255)->nullable();
            $table->string('foto_absen_masuk', 255)->nullable();
            $table->string('foto_absen_pulang', 255)->nullable();
            $table->enum('status_kehadiran', ['hadir', 'terlambat', 'izin', 'sakit', 'alpa'])->default('hadir');
            $table->integer('keterlambatan_menit')->default(0);
            $table->enum('metode_absen_masuk', ['manual', 'qr_code', 'pin'])->default('manual');
            $table->enum('metode_absen_pulang', ['manual', 'qr_code', 'pin'])->default('manual');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
