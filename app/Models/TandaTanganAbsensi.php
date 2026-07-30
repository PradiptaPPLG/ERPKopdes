<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TandaTanganAbsensi extends Model
{
    protected $table = 'tanda_tangan_absensi';

    protected $fillable = [
        'absensi_id', 'user_id',
        'ttd_masuk', 'ttd_pulang',
        'status_verifikasi', 'verifikator_id', 'catatan_verifikasi',
    ];

    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'absensi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}
