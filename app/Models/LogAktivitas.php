<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'aksi', 'deskripsi', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to record activity from anywhere.
     */
    public static function catat(string $aksi, string $deskripsi, ?int $userId = null): void
    {
        static::create([
            'user_id'    => $userId ?? auth()->id(),
            'aksi'       => $aksi,
            'deskripsi'  => $deskripsi,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
