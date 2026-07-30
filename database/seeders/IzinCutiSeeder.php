<?php

namespace Database\Seeders;

use App\Models\IzinCuti;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IzinCutiSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $adminId = User::where('jabatan', 'admin')->value('id');

        $samples = [
            [
                'user_id'         => $users->where('jabatan', 'kasir')->first()?->id ?? 5,
                'jenis'           => 'cuti_tahunan',
                'tanggal_mulai'   => Carbon::now()->addDays(5)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'alasan'          => 'Liburan keluarga ke kampung halaman',
                'status'          => 'pending',
                'approver_id'     => null,
            ],
            [
                'user_id'         => $users->where('jabatan', 'petugas_toko')->first()?->id ?? 7,
                'jenis'           => 'sakit',
                'tanggal_mulai'   => Carbon::now()->subDays(3)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'alasan'          => 'Demam dan flu berat, dokter menyarankan istirahat',
                'status'          => 'disetujui',
                'approver_id'     => $adminId,
                'catatan_approver' => 'Disetujui. Semoga cepat sembuh.',
            ],
            [
                'user_id'         => $users->where('jabatan', 'sekretaris')->first()?->id ?? 3,
                'jenis'           => 'dinas_luar',
                'tanggal_mulai'   => Carbon::now()->addDays(10)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(11)->format('Y-m-d'),
                'alasan'          => 'Menghadiri pelatihan manajemen koperasi di Kota Bandung',
                'status'          => 'disetujui',
                'approver_id'     => $adminId,
                'catatan_approver' => 'Disetujui. Harap buat laporan setelah kembali.',
            ],
            [
                'user_id'         => $users->where('jabatan', 'bendahara')->first()?->id ?? 4,
                'jenis'           => 'izin_pribadi',
                'tanggal_mulai'   => Carbon::now()->subDays(7)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'alasan'          => 'Ada keperluan keluarga mendadak',
                'status'          => 'ditolak',
                'approver_id'     => $adminId,
                'catatan_approver' => 'Tidak bisa disetujui karena bertepatan dengan tutup buku bulanan.',
            ],
            [
                'user_id'         => $users->where('email', 'kasir2@kopdes.id')->first()?->id ?? 6,
                'jenis'           => 'cuti_tahunan',
                'tanggal_mulai'   => Carbon::now()->addDays(15)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(19)->format('Y-m-d'),
                'alasan'          => 'Acara pernikahan saudara di luar kota',
                'status'          => 'pending',
                'approver_id'     => null,
            ],
        ];

        foreach ($samples as $izin) {
            IzinCuti::create($izin);
        }
    }
}
