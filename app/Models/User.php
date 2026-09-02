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
        'need_password_change', 'recovery_email', 'id_card_theme'
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
            'id_card_theme'           => 'integer',
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

    // ── Gamifikasi ID Card ───────────────────────────────────────
    public function getAttendanceCountAttribute(): int
    {
        return $this->absensi()->count();
    }

    public static function getCardTiers(): array
    {
        return [
            1 => ['name' => 'Putih (Default)', 'days' => 0, 'style' => 'background: #ffffff;'],
            2 => ['name' => 'Coklat', 'days' => 15, 'style' => 'background: linear-gradient(135deg, #a16207 0%, #713f12 100%);'],
            3 => ['name' => 'Silver', 'days' => 45, 'style' => 'background: linear-gradient(135deg, #94a3b8 0%, #475569 100%);'],
            4 => ['name' => 'Emas', 'days' => 80, 'style' => 'background: linear-gradient(135deg, #eab308 0%, #a16207 100%);'],
            5 => ['name' => 'Biru Berlian', 'days' => 100, 'style' => 'background: linear-gradient(135deg, #06b6d4 0%, #0369a1 100%);'],
            6 => ['name' => 'Ungu', 'days' => 150, 'style' => 'background: linear-gradient(135deg, #a855f7 0%, #6b21a8 100%);'],
            7 => ['name' => 'Oren', 'days' => 200, 'style' => 'background: linear-gradient(135deg, #f97316 0%, #c2410c 100%);'],
            8 => ['name' => 'Merah', 'days' => 400, 'style' => 'background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);'],
            9 => ['name' => 'Gradasi Merah Biru', 'days' => 600, 'style' => 'background: linear-gradient(135deg, #ef4444 0%, #3b82f6 100%);'],
            10 => ['name' => 'Peak Cosmic', 'days' => 1000, 'style' => 'background: linear-gradient(45deg, #ff00cc, #3333ff, #ff00cc); background-size: 400% 400%; animation: gradientBG 15s ease infinite;'],
        ];
    }

    public function getUnlockedTiersAttribute(): array
    {
        $tiers = self::getCardTiers();
        $unlocked = [];
        $isManagerial = $this->isManagerial(); // Admin & Ketua unlock semua tier
        $attendanceCount = $this->attendance_count;

        foreach ($tiers as $level => $tier) {
            if ($isManagerial || $attendanceCount >= $tier['days']) {
                $unlocked[] = $level;
            }
        }
        return $unlocked;
    }

    public function getCardThemeStyleAttribute(): string
    {
        $themeId = $this->id_card_theme ?: 1;
        $tiers = self::getCardTiers();
        
        // Fallback to blue (old default) if theme 1 is somehow empty or fallback to white. 
        // We'll use the one defined in getCardTiers().
        return $tiers[$themeId]['style'] ?? $tiers[1]['style'];
    }

    // ── Helpers ──────────────────────────────────────────────────

    /** Hanya jabatan admin (super admin) */
    public function isAdmin(): bool
    {
        return $this->jabatan === 'admin';
    }

    /** Hanya jabatan ketua (manager kopdes) */
    public function isKetua(): bool
    {
        return $this->jabatan === 'ketua';
    }

    /** Admin ATAU Ketua — untuk logika tier & monitoring */
    public function isManagerial(): bool
    {
        return in_array($this->jabatan, ['admin', 'ketua']);
    }

    public function canApprove(): bool
    {
        return in_array($this->jabatan, ['admin', 'ketua', 'sekretaris']);
    }

    /** Jabatan yang tidak memiliki shift kerja */
    public function hasNoShift(): bool
    {
        return in_array($this->jabatan, ['admin', 'ketua']);
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

    // ── QR Code Login ────────────────────────────────────────────
    public function getQrLoginPayloadAttribute(): string
    {
        // Format: qrlogin|{user_id}|{hmac_hash}
        // hmac_hash = hash_hmac('sha256', NIK, APP_KEY)
        $secret = config('app.key');
        $hash = hash_hmac('sha256', $this->nik ?? $this->email, $secret);
        return 'qrlogin|' . $this->id . '|' . $hash;
    }

    public function getQrCodeSvgAttribute(): string
    {
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(150, 0),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        return $writer->writeString($this->qr_login_payload);
    }
}
