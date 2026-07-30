<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'nama_shift', 'kode_warna', 'jam_mulai',
        'jam_selesai', 'durasi_menit',
        'toleransi_keterlambatan_menit', 'deskripsi',
    ];

    // ── Relationships ────────────────────────────────────────────
    public function users()
    {
        return $this->hasMany(User::class, 'shift_default_id');
    }

    public function jadwalShifts()
    {
        return $this->hasMany(JadwalShift::class);
    }

    // ── Accessors ────────────────────────────────────────────────
    public function getJamMulaiFormatAttribute(): string
    {
        return substr($this->jam_mulai, 0, 5);
    }

    public function getJamSelesaiFormatAttribute(): string
    {
        return substr($this->jam_selesai, 0, 5);
    }

    public function getDurasiJamAttribute(): string
    {
        $jam   = intdiv($this->durasi_menit, 60);
        $menit = $this->durasi_menit % 60;
        return $menit > 0 ? "{$jam} jam {$menit} menit" : "{$jam} jam";
    }
}
