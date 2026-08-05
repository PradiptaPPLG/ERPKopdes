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
        // Load users with their assigned Kopdes
        $users = User::with('kopdes')->where('status', 'aktif')->get();
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

                // Ambil koordinat Kopdes penugasan karyawan
                $kopdes = $user->kopdes;
                $latBase = $kopdes ? (double)$kopdes->latitude : -6.200000;
                $lngBase = $kopdes ? (double)$kopdes->longitude : 106.816666;
                $namaKopdes = $kopdes ? $kopdes->nama : 'Kopdes';

                $absensi = Absensi::create([
                    'user_id'           => $user->id,
                    'jadwal_id'         => $jadwal->id,
                    'tanggal'           => $tanggal->format('Y-m-d'),
                    'jam_masuk'         => $jamMasukActual->format('H:i:s'),
                    'jam_pulang'        => $jamPulangActual->format('H:i:s'),
                    // Beri deviasi acak tipis di sekitar kantor (dalam radius ~50 meter)
                    'latitude_masuk'    => $latBase + (rand(-50, 50) / 1000000),
                    'longitude_masuk'   => $lngBase + (rand(-50, 50) / 1000000),
                    'latitude_pulang'   => $latBase + (rand(-50, 50) / 1000000),
                    'longitude_pulang'  => $lngBase + (rand(-50, 50) / 1000000),
                    'lokasi_masuk'      => 'Kantor ' . $namaKopdes,
                    'lokasi_pulang'     => 'Kantor ' . $namaKopdes,
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
