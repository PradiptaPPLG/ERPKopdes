<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\JadwalShift;
use App\Models\TandaTanganAbsensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('status', 'aktif')->get();
        $today = Carbon::today();

        // Seed last 30 days of attendance
        for ($i = 29; $i >= 0; $i--) {
            $tanggal = $today->copy()->subDays($i);

            // Skip weekends
            if ($tanggal->isWeekend()) continue;

            foreach ($users as $user) {
                $shift     = $user->shiftDefault ?? \App\Models\Shift::first();
                $jamMulai  = Carbon::parse($tanggal->format('Y-m-d') . ' ' . $shift->jam_mulai);
                $jamSelesai = Carbon::parse($tanggal->format('Y-m-d') . ' ' . $shift->jam_selesai);

                // Create or get jadwal
                $jadwal = JadwalShift::firstOrCreate(
                    ['user_id' => $user->id, 'tanggal' => $tanggal->format('Y-m-d')],
                    [
                        'shift_id'   => $shift->id,
                        'status'     => 'terjadwal',
                        'created_by' => 1,
                    ]
                );

                // Random attendance patterns
                $rand = rand(1, 10);

                if ($rand <= 1) {
                    // 10% alpa
                    $jadwal->update(['status' => 'tidak_hadir']);
                    continue;
                }

                if ($rand == 2) {
                    // 10% izin
                    $jadwal->update(['status' => 'izin']);
                    continue;
                }

                // Determine lateness
                $terlambatMenit = 0;
                $statusKehadiran = 'hadir';
                if ($rand == 3) {
                    // 10% terlambat
                    $terlambatMenit  = rand(5, 45);
                    $statusKehadiran = 'terlambat';
                }

                $jamMasukActual  = $jamMulai->copy()->addMinutes($terlambatMenit)->addSeconds(rand(0, 59));
                $jamPulangActual = $jamSelesai->copy()->addMinutes(rand(-10, 30));

                $absensi = Absensi::create([
                    'user_id'           => $user->id,
                    'jadwal_id'         => $jadwal->id,
                    'tanggal'           => $tanggal->format('Y-m-d'),
                    'jam_masuk'         => $jamMasukActual->format('H:i:s'),
                    'jam_pulang'        => $jamPulangActual->format('H:i:s'),
                    'latitude_masuk'    => -6.200000 + (rand(-100, 100) / 10000),
                    'longitude_masuk'   => 106.816666 + (rand(-100, 100) / 10000),
                    'latitude_pulang'   => -6.200000 + (rand(-100, 100) / 10000),
                    'longitude_pulang'  => 106.816666 + (rand(-100, 100) / 10000),
                    'lokasi_masuk'      => 'Kantor Koperasi Desa Maju Bersama',
                    'lokasi_pulang'     => 'Kantor Koperasi Desa Maju Bersama',
                    'status_kehadiran'  => $statusKehadiran,
                    'keterlambatan_menit' => $terlambatMenit,
                    'metode_absen_masuk'  => 'manual',
                    'metode_absen_pulang' => 'manual',
                ]);

                $jadwal->update(['status' => 'hadir']);

                // Create signature record
                TandaTanganAbsensi::create([
                    'absensi_id'       => $absensi->id,
                    'user_id'          => $user->id,
                    'status_verifikasi' => rand(0, 1) ? 'terverifikasi' : 'pending',
                    'verifikator_id'   => rand(0, 1) ? 1 : null,
                ]);
            }
        }
    }
}
