<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'user_id', 'jadwal_id', 'tanggal',
        'jam_masuk', 'jam_pulang',
        'latitude_masuk', 'longitude_masuk',
        'latitude_pulang', 'longitude_pulang',
        'lokasi_masuk', 'lokasi_pulang',
        'foto_absen_masuk', 'foto_absen_pulang',
        'status_kehadiran', 'keterlambatan_menit',
        'metode_absen_masuk', 'metode_absen_pulang', 'catatan',
    ];

    protected $casts = [
        'tanggal'           => 'date',
        'latitude_masuk'    => 'float',
        'longitude_masuk'   => 'float',
        'latitude_pulang'   => 'float',
        'longitude_pulang'  => 'float',
    ];

    // ── Relationships ────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalShift::class, 'jadwal_id');
    }

    public function tandaTangan()
    {
        return $this->hasOne(TandaTanganAbsensi::class, 'absensi_id');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function statusLabel(): string
    {
        return match ($this->status_kehadiran) {
            'hadir'            => 'Hadir',
            'terlambat'        => 'Terlambat',
            'sangat_terlambat' => 'Sangat Terlambat',
            'izin'             => 'Izin',
            'sakit'            => 'Sakit',
            'alpa'             => 'Alpa',
            default            => ucfirst($this->status_kehadiran),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status_kehadiran) {
            'hadir'            => 'success',
            'terlambat'        => 'warning',
            'sangat_terlambat' => 'danger',
            'izin'             => 'info',
            'sakit'            => 'warning',
            'alpa'             => 'danger',
            default            => 'secondary',
        };
    }

    /**
     * Is considered late (terlambat or sangat_terlambat)
     */
    public function isLate(): bool
    {
        return in_array($this->status_kehadiran, ['terlambat', 'sangat_terlambat']);
    }

    /**
     * Is sangat terlambat (>= 31 minutes)
     */
    public function isSangatTerlambat(): bool
    {
        return $this->status_kehadiran === 'sangat_terlambat';
    }

    public function durasiKerja(): string
    {
        if (!$this->jam_masuk || !$this->jam_pulang) return '-';
        $masuk  = \Carbon\Carbon::parse($this->jam_masuk);
        $pulang = \Carbon\Carbon::parse($this->jam_pulang);
        $diff   = $masuk->diff($pulang);
        return $diff->h . ' jam ' . $diff->i . ' menit';
    }
}
