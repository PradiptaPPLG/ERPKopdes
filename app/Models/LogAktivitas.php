<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'aksi', 'deskripsi', 'ip_address', 'user_agent', 'hash',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate HMAC integrity signature for log data.
     */
    public function generateSignature(): string
    {
        $data = implode('|', [
            $this->user_id,
            $this->aksi,
            $this->deskripsi,
            $this->ip_address,
            $this->user_agent,
            $this->created_at ? $this->created_at->toDateTimeString() : now()->toDateTimeString(),
        ]);

        return hash_hmac('sha256', $data, config('app.key'));
    }

    /**
     * Check if the log has been modified directly in the database.
     */
    public function isValidHash(): bool
    {
        if (is_null($this->hash)) {
            return true; // Log lama sebelum kolom hash diimplementasikan
        }

        return hash_equals($this->hash, $this->generateSignature());
    }

    /**
     * Boot model events to automatically hash logs on creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->created_at) {
                $model->created_at = now();
            }
            $model->hash = $model->generateSignature();
        });
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

