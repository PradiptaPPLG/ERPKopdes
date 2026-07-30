<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IzinCuti extends Model
{
    protected $table = 'izin_cuti';

    protected $fillable = [
        'user_id', 'jenis', 'tanggal_mulai', 'tanggal_selesai',
        'alasan', 'lampiran', 'status', 'approver_id', 'catatan_approver',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    // ── Relationships ────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function jenisLabel(): string
    {
        return match ($this->jenis) {
            'cuti_tahunan'  => 'Cuti Tahunan',
            'sakit'         => 'Sakit',
            'izin_pribadi'  => 'Izin Pribadi',
            'dinas_luar'    => 'Dinas Luar',
            default         => ucfirst($this->jenis),
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'    => 'Menunggu',
            'disetujui'  => 'Disetujui',
            'ditolak'    => 'Ditolak',
            default      => ucfirst($this->status),
        };
    }

    public function jumlahHari(): int
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }
}
