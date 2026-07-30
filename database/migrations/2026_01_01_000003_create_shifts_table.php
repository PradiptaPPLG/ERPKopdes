<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_shift', 20);
            $table->string('kode_warna', 7)->default('#cc0000');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('durasi_menit')->default(0);
            $table->integer('toleransi_keterlambatan_menit')->default(15);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
