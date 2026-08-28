<?php

namespace Database\Seeders;

use App\Models\Kopdes;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID Kopdes berdasarkan nama
        $kopdesCijeungjing = Kopdes::where('nama', 'Kopdes Cijeungjing')->first()?->id;
        $kopdesDago        = Kopdes::where('nama', 'Kopdes Dago')->first()?->id;
        $kopdesMalioboro   = Kopdes::where('nama', 'Kopdes Malioboro')->first()?->id;
        $kopdesKuta        = Kopdes::where('nama', 'Kopdes Kuta')->first()?->id;
        $kopdesUbud        = Kopdes::where('nama', 'Kopdes Ubud')->first()?->id;
        $kopdesMenteng     = Kopdes::where('nama', 'Kopdes Menteng')->first()?->id;
        $kopdesGubeng      = Kopdes::where('nama', 'Kopdes Gubeng')->first()?->id;
        $kopdesSimpangLima = Kopdes::where('nama', 'Kopdes Simpang Lima')->first()?->id;
        $kopdesMedanBaru   = Kopdes::where('nama', 'Kopdes Medan Baru')->first()?->id;

        $users = [
            // Admin (ditugaskan di Kopdes Menteng - Jakarta)
            [
                'name'            => 'Administrator Kopdes',
                'email'           => 'admin@kopdes.id',
                'password'        => Hash::make('admin123'),
                'need_password_change' => false,
                'recovery_email'  => 'pradipta02032009@gmail.com',
                'nik'             => '3271010101800001',
                'nip'             => 'KD-2020-0001',
                'tempat_lahir'    => 'Jakarta',
                'tanggal_lahir'   => '1980-01-01',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Teuku Umar No. 10, Menteng, Jakarta Pusat',
                'no_hp'           => '081234567890',
                'jabatan'         => 'admin',
                'status'          => 'aktif',
                'shift_default_id' => 3,
                'kopdes_id'       => $kopdesMenteng,
            ],
            // Ketua (ditugaskan di Kopdes Cijeungjing - Ciamis)
            [
                'name'            => 'Budi Santoso',
                'email'           => 'ketua@kopdes.id',
                'password'        => Hash::make('ketua123'),
                'need_password_change' => false,
                'recovery_email'  => 'pradipta02032009@gmail.com',
                'nik'             => '3271010201750002',
                'nip'             => 'KD-2018-0002',
                'tempat_lahir'    => 'Ciamis',
                'tanggal_lahir'   => '1975-02-15',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Pahlawan No. 45, Cijeungjing, Ciamis',
                'no_hp'           => '082345678901',
                'jabatan'         => 'ketua',
                'status'          => 'aktif',
                'shift_default_id' => 3,
                'kopdes_id'       => $kopdesCijeungjing,
            ],
            // Sekretaris (ditugaskan di Kopdes Dago - Bandung)
            [
                'name'            => 'Siti Rahayu',
                'email'           => 'sekretaris@kopdes.id',
                'password'        => Hash::make('sekretaris123'),
                'need_password_change' => false,
                'recovery_email'  => 'pradipta02032009@gmail.com',
                'nik'             => '3271014503820003',
                'nip'             => 'KD-2019-0003',
                'tempat_lahir'    => 'Bandung',
                'tanggal_lahir'   => '1982-03-20',
                'jenis_kelamin'   => 'P',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Dago Elos No. 12, Coblong, Bandung',
                'no_hp'           => '083456789012',
                'jabatan'         => 'sekretaris',
                'status'          => 'aktif',
                'shift_default_id' => 3,
                'kopdes_id'       => $kopdesDago,
            ],
            // Bendahara (ditugaskan di Kopdes Malioboro - Yogyakarta)
            [
                'name'            => 'Ahmad Fauzi',
                'email'           => 'bendahara@kopdes.id',
                'password'        => Hash::make('bendahara123'),
                'need_password_change' => false,
                'recovery_email'  => 'pradipta02032009@gmail.com',
                'nik'             => '3271010104850004',
                'nip'             => 'KD-2019-0004',
                'tempat_lahir'    => 'Yogyakarta',
                'tanggal_lahir'   => '1985-04-10',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Sosrowijayan No. 7, Gedongtengen, Yogyakarta',
                'no_hp'           => '084567890123',
                'jabatan'         => 'bendahara',
                'status'          => 'aktif',
                'shift_default_id' => 3,
                'kopdes_id'       => $kopdesMalioboro,
            ],
            // Kasir 1 (ditugaskan di Kopdes Ubud - Bali)
            [
                'name'            => 'Dewi Permata',
                'email'           => 'kasir1@kopdes.id',
                'password'        => Hash::make('kasir123'),
                'need_password_change' => false,
                'recovery_email'  => 'pradipta02032009@gmail.com',
                'nik'             => '3271014504900005',
                'nip'             => 'KD-2021-0005',
                'tempat_lahir'    => 'Denpasar',
                'tanggal_lahir'   => '1990-05-25',
                'jenis_kelamin'   => 'P',
                'agama'           => 'Kristen',
                'alamat'          => 'Jl. Raya Andong No. 3, Ubud, Gianyar',
                'no_hp'           => '085678901234',
                'jabatan'         => 'kasir',
                'status'          => 'aktif',
                'shift_default_id' => 1,
                'kopdes_id'       => $kopdesUbud,
            ],
            // Kasir 2 (ditugaskan di Kopdes Kuta - Bali)
            [
                'name'            => 'Rizky Pratama',
                'email'           => 'kasir2@kopdes.id',
                'password'        => Hash::make('kasir123'),
                'need_password_change' => false,
                'recovery_email'  => 'pradipta02032009@gmail.com',
                'nik'             => '3271010106920006',
                'nip'             => 'KD-2022-0006',
                'tempat_lahir'    => 'Badung',
                'tanggal_lahir'   => '1992-06-08',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Legian No. 9, Kuta, Badung, Bali',
                'no_hp'           => '086789012345',
                'jabatan'         => 'kasir',
                'status'          => 'aktif',
                'shift_default_id' => 2,
                'kopdes_id'       => $kopdesKuta,
            ],
            // Petugas Toko 1 (ditugaskan di Kopdes Gubeng - Surabaya)
            [
                'name'            => 'Rina Wulandari',
                'email'           => 'petugas1@kopdes.id',
                'password'        => Hash::make('petugas123'),
                'need_password_change' => false,
                'recovery_email'  => 'pradipta02032009@gmail.com',
                'nik'             => '3271014507950007',
                'nip'             => 'KD-2022-0007',
                'tempat_lahir'    => 'Surabaya',
                'tanggal_lahir'   => '1995-07-14',
                'jenis_kelamin'   => 'P',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Dharmahusada No. 5, Gubeng, Surabaya',
                'no_hp'           => '087890123456',
                'jabatan'         => 'petugas_toko',
                'status'          => 'aktif',
                'shift_default_id' => 1,
                'kopdes_id'       => $kopdesGubeng,
            ],
            // Petugas Toko 2 (ditugaskan di Kopdes Simpang Lima - Semarang)
            [
                'name'            => 'Hendra Gunawan',
                'email'           => 'petugas2@kopdes.id',
                'password'        => Hash::make('petugas123'),
                'need_password_change' => false,
                'recovery_email'  => 'pradipta02032009@gmail.com',
                'nik'             => '3271010108930008',
                'nip'             => 'KD-2023-0008',
                'tempat_lahir'    => 'Semarang',
                'tanggal_lahir'   => '1993-08-30',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Ahmad Yani No. 11, Pleburan, Semarang',
                'no_hp'           => '088901234567',
                'jabatan'         => 'petugas_toko',
                'status'          => 'aktif',
                'shift_default_id' => 2,
                'kopdes_id'       => $kopdesSimpangLima,
            ],
            // Cuti user (ditugaskan di Kopdes Medan Baru - Medan)
            [
                'name'            => 'Maya Lestari',
                'email'           => 'maya@kopdes.id',
                'password'        => Hash::make('maya123'),
                'need_password_change' => false,
                'recovery_email'  => 'pradipta02032009@gmail.com',
                'nik'             => '3271014509880009',
                'nip'             => 'KD-2021-0009',
                'tempat_lahir'    => 'Medan',
                'tanggal_lahir'   => '1988-09-19',
                'jenis_kelamin'   => 'P',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Dr. Mansyur No. 15, Padang Bulan, Medan',
                'no_hp'           => '089012345678',
                'jabatan'         => 'petugas_toko',
                'status'          => 'cuti',
                'shift_default_id' => 1,
                'kopdes_id'       => $kopdesMedanBaru,
            ],
            // User Dummy 1
            [
                'name'            => 'Pradipta Endra Maulana',
                'email'           => 'pradipta02032009@gmail.com',
                'password'        => Hash::make('02032009'),
                'need_password_change' => false,
                'recovery_email'  => 'pradipta02032009@gmail.com',
                'nik'             => '3276389173647382',
                'nip'             => 'KD-2009-0010',
                'tempat_lahir'    => 'Ciamis',
                'tanggal_lahir'   => '2009-03-02',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Pahlawan No. 45, Cijeungjing, Ciamis',
                'no_hp'           => '085219583336',
                'jabatan'         => 'petugas_toko',
                'status'          => 'aktif',
                'shift_default_id' => 1,
                'kopdes_id'       => $kopdesCijeungjing,
            ],
            // User Dummy 2
            [
                'name'            => 'Fathin',
                'email'           => 'fathin@gmail.com',
                'password'        => Hash::make('fathin123'),
                'need_password_change' => false,
                'recovery_email'  => 'pradipta02032009@gmail.com',
                'nik'             => '3276378473891237',
                'nip'             => 'KD-2009-0011',
                'tempat_lahir'    => 'Ciamis',
                'tanggal_lahir'   => '2009-05-04',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Pahlawan No. 45, Cijeungjing, Ciamis',
                'no_hp'           => '085273846663',
                'jabatan'         => 'petugas_toko',
                'status'          => 'aktif',
                'shift_default_id' => 1,
                'kopdes_id'       => $kopdesCijeungjing,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}

