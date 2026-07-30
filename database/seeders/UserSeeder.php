<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Admin
            [
                'name'            => 'Administrator Kopdes',
                'email'           => 'admin@kopdes.id',
                'password'        => Hash::make('admin123'),
                'nik'             => '3271010101800001',
                'nip'             => 'KD-2020-0001',
                'tempat_lahir'    => 'Bandung',
                'tanggal_lahir'   => '1980-01-01',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Desa Maju No. 1, RT 01/RW 01, Desa Maju Bersama',
                'no_hp'           => '081234567890',
                'jabatan'         => 'admin',
                'status'          => 'aktif',
                'shift_default_id' => 3,
            ],
            // Ketua
            [
                'name'            => 'Budi Santoso',
                'email'           => 'ketua@kopdes.id',
                'password'        => Hash::make('ketua123'),
                'nik'             => '3271010201750002',
                'nip'             => 'KD-2018-0002',
                'tempat_lahir'    => 'Jakarta',
                'tanggal_lahir'   => '1975-02-15',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Pahlawan No. 45, RT 02/RW 03, Desa Maju Bersama',
                'no_hp'           => '082345678901',
                'jabatan'         => 'ketua',
                'status'          => 'aktif',
                'shift_default_id' => 3,
            ],
            // Sekretaris
            [
                'name'            => 'Siti Rahayu',
                'email'           => 'sekretaris@kopdes.id',
                'password'        => Hash::make('sekretaris123'),
                'nik'             => '3271014503820003',
                'nip'             => 'KD-2019-0003',
                'tempat_lahir'    => 'Bogor',
                'tanggal_lahir'   => '1982-03-20',
                'jenis_kelamin'   => 'P',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Melati No. 12, RT 03/RW 02, Desa Maju Bersama',
                'no_hp'           => '083456789012',
                'jabatan'         => 'sekretaris',
                'status'          => 'aktif',
                'shift_default_id' => 3,
            ],
            // Bendahara
            [
                'name'            => 'Ahmad Fauzi',
                'email'           => 'bendahara@kopdes.id',
                'password'        => Hash::make('bendahara123'),
                'nik'             => '3271010104850004',
                'nip'             => 'KD-2019-0004',
                'tempat_lahir'    => 'Depok',
                'tanggal_lahir'   => '1985-04-10',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Mawar No. 7, RT 04/RW 01, Desa Maju Bersama',
                'no_hp'           => '084567890123',
                'jabatan'         => 'bendahara',
                'status'          => 'aktif',
                'shift_default_id' => 3,
            ],
            // Kasir 1
            [
                'name'            => 'Dewi Permata',
                'email'           => 'kasir1@kopdes.id',
                'password'        => Hash::make('kasir123'),
                'nik'             => '3271014504900005',
                'nip'             => 'KD-2021-0005',
                'tempat_lahir'    => 'Bekasi',
                'tanggal_lahir'   => '1990-05-25',
                'jenis_kelamin'   => 'P',
                'agama'           => 'Kristen',
                'alamat'          => 'Jl. Anggrek No. 3, RT 01/RW 04, Desa Maju Bersama',
                'no_hp'           => '085678901234',
                'jabatan'         => 'kasir',
                'status'          => 'aktif',
                'shift_default_id' => 1,
            ],
            // Kasir 2
            [
                'name'            => 'Rizky Pratama',
                'email'           => 'kasir2@kopdes.id',
                'password'        => Hash::make('kasir123'),
                'nik'             => '3271010106920006',
                'nip'             => 'KD-2022-0006',
                'tempat_lahir'    => 'Tangerang',
                'tanggal_lahir'   => '1992-06-08',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Dahlia No. 9, RT 02/RW 02, Desa Maju Bersama',
                'no_hp'           => '086789012345',
                'jabatan'         => 'kasir',
                'status'          => 'aktif',
                'shift_default_id' => 2,
            ],
            // Petugas Toko 1
            [
                'name'            => 'Rina Wulandari',
                'email'           => 'petugas1@kopdes.id',
                'password'        => Hash::make('petugas123'),
                'nik'             => '3271014507950007',
                'nip'             => 'KD-2022-0007',
                'tempat_lahir'    => 'Cianjur',
                'tanggal_lahir'   => '1995-07-14',
                'jenis_kelamin'   => 'P',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Kenanga No. 5, RT 03/RW 03, Desa Maju Bersama',
                'no_hp'           => '087890123456',
                'jabatan'         => 'petugas_toko',
                'status'          => 'aktif',
                'shift_default_id' => 1,
            ],
            // Petugas Toko 2
            [
                'name'            => 'Hendra Gunawan',
                'email'           => 'petugas2@kopdes.id',
                'password'        => Hash::make('petugas123'),
                'nik'             => '3271010108930008',
                'nip'             => 'KD-2023-0008',
                'tempat_lahir'    => 'Sukabumi',
                'tanggal_lahir'   => '1993-08-30',
                'jenis_kelamin'   => 'L',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Cempaka No. 11, RT 04/RW 04, Desa Maju Bersama',
                'no_hp'           => '088901234567',
                'jabatan'         => 'petugas_toko',
                'status'          => 'aktif',
                'shift_default_id' => 2,
            ],
            // Cuti user
            [
                'name'            => 'Maya Lestari',
                'email'           => 'maya@kopdes.id',
                'password'        => Hash::make('maya123'),
                'nik'             => '3271014509880009',
                'nip'             => 'KD-2021-0009',
                'tempat_lahir'    => 'Garut',
                'tanggal_lahir'   => '1988-09-19',
                'jenis_kelamin'   => 'P',
                'agama'           => 'Islam',
                'alamat'          => 'Jl. Seroja No. 15, RT 05/RW 01, Desa Maju Bersama',
                'no_hp'           => '089012345678',
                'jabatan'         => 'petugas_toko',
                'status'          => 'cuti',
                'shift_default_id' => 1,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
