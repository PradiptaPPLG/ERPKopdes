<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ShiftSeeder::class,    // Must run first (users FK to shifts)
            UserSeeder::class,
            AbsensiSeeder::class,  // Creates jadwal_shifts + absensi + tanda_tangan
            IzinCutiSeeder::class,
        ]);
    }
}
