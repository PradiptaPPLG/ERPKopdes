<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalShift extends Model
{
    protected $table = 'jadwal_shifts';

    protected $fillable = [
        'user_id', 'shift_id', 'tanggal', 'status', 'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // ── Relationships ────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function absensi()
    {
        return $this->hasOne(Absensi::class, 'jadwal_id');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function statusLabel(): string
    {
        return match ($this->status) {
            'terjadwal'    => 'Terjadwal',
            'hadir'        => 'Hadir',
            'tidak_hadir'  => 'Tidak Hadir',
            'izin'         => 'Izin',
            'sakit'        => 'Sakit',
            default        => ucfirst($this->status),
        };
    }
}
