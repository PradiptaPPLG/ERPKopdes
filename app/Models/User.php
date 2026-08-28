<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'nik', 'nip', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'agama', 'alamat', 'no_hp',
        'jabatan', 'status', 'foto_profil',
        'tanda_tangan_digital', 'shift_default_id', 'kopdes_id',
        'two_factor_secret', 'two_factor_confirmed_at',
        'need_password_change', 'recovery_email',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'password'                => 'hashed',
            'tanggal_lahir'           => 'date',
            'two_factor_confirmed_at' => 'datetime',
            'need_password_change'    => 'boolean',
        ];
    }

    // ── Relationships ────────────────────────────────────────────
    public function kopdes()
    {
        return $this->belongsTo(Kopdes::class, 'kopdes_id');
    }

    public function shiftDefault()
    {
        return $this->belongsTo(Shift::class, 'shift_default_id');
    }

    public function jadwalShifts()
    {
        return $this->hasMany(JadwalShift::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function izinCuti()
    {
        return $this->hasMany(IzinCuti::class);
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false)->latest();
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return in_array($this->jabatan, ['admin', 'ketua']);
    }

    public function canApprove(): bool
    {
        return in_array($this->jabatan, ['admin', 'ketua', 'sekretaris']);
    }

    public function jabatanLabel(): string
    {
        return match ($this->jabatan) {
            'admin'         => 'Administrator',
            'ketua'         => 'Ketua',
            'sekretaris'    => 'Sekretaris',
            'bendahara'     => 'Bendahara',
            'kasir'         => 'Kasir',
            'petugas_toko'  => 'Petugas Toko',
            default         => ucfirst($this->jabatan),
        };
    }

    public function absensiHariIni()
    {
        return $this->absensi()->where('tanggal', today())->first();
    }

    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_secret) && !is_null($this->two_factor_confirmed_at);
    }
}
