<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            [
                'nama_shift'                     => 'Pagi',
                'kode_warna'                     => '#10b981',
                'jam_mulai'                      => '07:00:00',
                'jam_selesai'                    => '14:00:00',
                'durasi_menit'                   => 420,
                'toleransi_keterlambatan_menit'  => 15,
                'deskripsi'                      => 'Shift pagi mulai pukul 07.00 - 14.00 WIB',
            ],
            [
                'nama_shift'                     => 'Siang',
                'kode_warna'                     => '#f59e0b',
                'jam_mulai'                      => '14:00:00',
                'jam_selesai'                    => '21:00:00',
                'durasi_menit'                   => 420,
                'toleransi_keterlambatan_menit'  => 15,
                'deskripsi'                      => 'Shift siang mulai pukul 14.00 - 21.00 WIB',
            ],
            [
                'nama_shift'                     => 'Full',
                'kode_warna'                     => '#ef4444',
                'jam_mulai'                      => '08:00:00',
                'jam_selesai'                    => '17:00:00',
                'durasi_menit'                   => 540,
                'toleransi_keterlambatan_menit'  => 15,
                'deskripsi'                      => 'Shift full day mulai pukul 08.00 - 17.00 WIB',
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}
