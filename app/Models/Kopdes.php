<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kopdes extends Model
{
    use HasFactory;

    protected $table = 'kopdes';

    protected $fillable = [
        'nama',
        'alamat',
        'latitude',
        'longitude',
        'radius_meter',
        'manager_id',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
    ];

    // Relasi ke User (Karyawan) yang ditugaskan di Kopdes ini
    public function users()
    {
        return $this->hasMany(User::class, 'kopdes_id');
    }

    // Relasi ke User (Manager Cabang)
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
