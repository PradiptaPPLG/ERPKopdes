<?php

namespace Database\Seeders;

use App\Models\Kopdes;
use Illuminate\Database\Seeder;

class KopdesSeeder extends Seeder
{
    public function run(): void
    {
        $koperasi = [
            // ── JAWA BARAT ──────────────────────────────────────────────
            [
                'nama' => 'Kopdes Cijeungjing',
                'alamat' => 'Jl. Raya Cijeungjing No. 45, Desa Cijeungjing, Ciamis',
                'latitude' => -7.33230680, 'longitude' => 108.38466180,
                'desa' => 'Cijeungjing', 'kecamatan' => 'Cijeungjing',
                'kabupaten' => 'Ciamis', 'provinsi' => 'Jawa Barat',
            ],
            [
                'nama' => 'Kopdes Dago',
                'alamat' => 'Jl. Ir. H. Juanda No. 102, Dago, Coblong, Bandung',
                'latitude' => -6.88680000, 'longitude' => 107.61530000,
                'desa' => 'Dago', 'kecamatan' => 'Coblong',
                'kabupaten' => 'Bandung', 'provinsi' => 'Jawa Barat',
            ],
            [
                'nama' => 'Kopdes Merdeka Bandung',
                'alamat' => 'Jl. Merdeka No. 56, Babakan Ciamis, Sumur Bandung',
                'latitude' => -6.91470000, 'longitude' => 107.60980000,
                'desa' => 'Babakan Ciamis', 'kecamatan' => 'Sumur Bandung',
                'kabupaten' => 'Bandung', 'provinsi' => 'Jawa Barat',
            ],
            [
                'nama' => 'Kopdes Sukabumi Selatan',
                'alamat' => 'Jl. Raya Pelabuhan Ratu No. 12, Cikembar, Sukabumi',
                'latitude' => -7.00350000, 'longitude' => 106.92300000,
                'desa' => 'Cikembar', 'kecamatan' => 'Cikembar',
                'kabupaten' => 'Sukabumi', 'provinsi' => 'Jawa Barat',
            ],
            [
                'nama' => 'Kopdes Garut Kota',
                'alamat' => 'Jl. Otista No. 33, Kota Kulon, Garut Kota',
                'latitude' => -7.21220000, 'longitude' => 107.90720000,
                'desa' => 'Kota Kulon', 'kecamatan' => 'Garut Kota',
                'kabupaten' => 'Garut', 'provinsi' => 'Jawa Barat',
            ],
            [
                'nama' => 'Kopdes Tasikmalaya Indah',
                'alamat' => 'Jl. HZ. Mustofa No. 77, Cikalong, Tasikmalaya',
                'latitude' => -7.32710000, 'longitude' => 108.21920000,
                'desa' => 'Cikalong', 'kecamatan' => 'Cihideung',
                'kabupaten' => 'Tasikmalaya', 'provinsi' => 'Jawa Barat',
            ],
            [
                'nama' => 'Kopdes Karawang Utara',
                'alamat' => 'Jl. Tuparev No. 18, Nagasari, Karawang Barat',
                'latitude' => -6.32610000, 'longitude' => 107.31890000,
                'desa' => 'Nagasari', 'kecamatan' => 'Karawang Barat',
                'kabupaten' => 'Karawang', 'provinsi' => 'Jawa Barat',
            ],
            [
                'nama' => 'Kopdes Bogor Tengah',
                'alamat' => 'Jl. Suryakencana No. 45, Babakan, Bogor Tengah',
                'latitude' => -6.59640000, 'longitude' => 106.79340000,
                'desa' => 'Babakan', 'kecamatan' => 'Bogor Tengah',
                'kabupaten' => 'Bogor', 'provinsi' => 'Jawa Barat',
            ],
            [
                'nama' => 'Kopdes Cirebon Barat',
                'alamat' => 'Jl. Cipto Mangunkusumo No. 10, Kejaksan, Cirebon',
                'latitude' => -6.74070000, 'longitude' => 108.55020000,
                'desa' => 'Kejaksan', 'kecamatan' => 'Kejaksan',
                'kabupaten' => 'Cirebon', 'provinsi' => 'Jawa Barat',
            ],

            // ── DKI JAKARTA ─────────────────────────────────────────────
            [
                'nama' => 'Kopdes Menteng',
                'alamat' => 'Jl. Teuku Umar No. 1, Menteng, Jakarta Pusat',
                'latitude' => -6.20140000, 'longitude' => 106.82940000,
                'desa' => 'Menteng', 'kecamatan' => 'Menteng',
                'kabupaten' => 'Jakarta Pusat', 'provinsi' => 'DKI Jakarta',
            ],
            [
                'nama' => 'Kopdes Kebayoran Baru',
                'alamat' => 'Jl. Melawai Raya No. 6, Kebayoran Baru, Jakarta Selatan',
                'latitude' => -6.24440000, 'longitude' => 106.80100000,
                'desa' => 'Melawai', 'kecamatan' => 'Kebayoran Baru',
                'kabupaten' => 'Jakarta Selatan', 'provinsi' => 'DKI Jakarta',
            ],
            [
                'nama' => 'Kopdes Penjaringan',
                'alamat' => 'Jl. Pluit Raya No. 20, Penjaringan, Jakarta Utara',
                'latitude' => -6.12360000, 'longitude' => 106.79380000,
                'desa' => 'Pluit', 'kecamatan' => 'Penjaringan',
                'kabupaten' => 'Jakarta Utara', 'provinsi' => 'DKI Jakarta',
            ],

            // ── BANTEN ──────────────────────────────────────────────────
            [
                'nama' => 'Kopdes Serang Kota',
                'alamat' => 'Jl. Veteran No. 5, Cipare, Serang',
                'latitude' => -6.11980000, 'longitude' => 106.15090000,
                'desa' => 'Cipare', 'kecamatan' => 'Serang',
                'kabupaten' => 'Serang', 'provinsi' => 'Banten',
            ],
            [
                'nama' => 'Kopdes Tangerang Selatan',
                'alamat' => 'Jl. Raya Serpong No. 40, BSD City, Tangerang Selatan',
                'latitude' => -6.30440000, 'longitude' => 106.66470000,
                'desa' => 'Serpong', 'kecamatan' => 'Serpong',
                'kabupaten' => 'Tangerang Selatan', 'provinsi' => 'Banten',
            ],

            // ── DI YOGYAKARTA ────────────────────────────────────────────
            [
                'nama' => 'Kopdes Malioboro',
                'alamat' => 'Jl. Malioboro No. 12, Sosromenduran, Gedongtengen, Yogyakarta',
                'latitude' => -7.79250000, 'longitude' => 110.36600000,
                'desa' => 'Sosromenduran', 'kecamatan' => 'Gedongtengen',
                'kabupaten' => 'Yogyakarta', 'provinsi' => 'DI Yogyakarta',
            ],
            [
                'nama' => 'Kopdes Sleman Timur',
                'alamat' => 'Jl. Kaliurang KM 8, Sinduharjo, Ngaglik, Sleman',
                'latitude' => -7.73820000, 'longitude' => 110.39870000,
                'desa' => 'Sinduharjo', 'kecamatan' => 'Ngaglik',
                'kabupaten' => 'Sleman', 'provinsi' => 'DI Yogyakarta',
            ],
            [
                'nama' => 'Kopdes Bantul Makmur',
                'alamat' => 'Jl. Parangtritis KM 5, Sewon, Bantul',
                'latitude' => -7.84890000, 'longitude' => 110.35020000,
                'desa' => 'Panggungharjo', 'kecamatan' => 'Sewon',
                'kabupaten' => 'Bantul', 'provinsi' => 'DI Yogyakarta',
            ],

            // ── JAWA TENGAH ──────────────────────────────────────────────
            [
                'nama' => 'Kopdes Simpang Lima',
                'alamat' => 'Jl. Pandanaran No. 10, Pleburan, Semarang Selatan',
                'latitude' => -6.98970000, 'longitude' => 110.42290000,
                'desa' => 'Pleburan', 'kecamatan' => 'Semarang Selatan',
                'kabupaten' => 'Semarang', 'provinsi' => 'Jawa Tengah',
            ],
            [
                'nama' => 'Kopdes Solo Kota',
                'alamat' => 'Jl. Slamet Riyadi No. 275, Sriwedari, Laweyan, Surakarta',
                'latitude' => -7.56600000, 'longitude' => 110.82490000,
                'desa' => 'Sriwedari', 'kecamatan' => 'Laweyan',
                'kabupaten' => 'Surakarta', 'provinsi' => 'Jawa Tengah',
            ],
            [
                'nama' => 'Kopdes Purwokerto Barat',
                'alamat' => 'Jl. Jend. Sudirman No. 50, Sokanegara, Purwokerto Timur',
                'latitude' => -7.42120000, 'longitude' => 109.23300000,
                'desa' => 'Sokanegara', 'kecamatan' => 'Purwokerto Timur',
                'kabupaten' => 'Banyumas', 'provinsi' => 'Jawa Tengah',
            ],
            [
                'nama' => 'Kopdes Magelang Kota',
                'alamat' => 'Jl. Pemuda No. 8, Cacaban, Magelang Tengah',
                'latitude' => -7.47020000, 'longitude' => 110.21760000,
                'desa' => 'Cacaban', 'kecamatan' => 'Magelang Tengah',
                'kabupaten' => 'Magelang', 'provinsi' => 'Jawa Tengah',
            ],
            [
                'nama' => 'Kopdes Kudus Santri',
                'alamat' => 'Jl. A. Yani No. 14, Kudus Kota, Kudus',
                'latitude' => -6.80420000, 'longitude' => 110.84060000,
                'desa' => 'Kudus', 'kecamatan' => 'Kota',
                'kabupaten' => 'Kudus', 'provinsi' => 'Jawa Tengah',
            ],

            // ── JAWA TIMUR ───────────────────────────────────────────────
            [
                'nama' => 'Kopdes Gubeng',
                'alamat' => 'Jl. Raya Gubeng No. 15, Gubeng, Surabaya',
                'latitude' => -7.27960000, 'longitude' => 112.75380000,
                'desa' => 'Gubeng', 'kecamatan' => 'Gubeng',
                'kabupaten' => 'Surabaya', 'provinsi' => 'Jawa Timur',
            ],
            [
                'nama' => 'Kopdes Malang Kota',
                'alamat' => 'Jl. Kawi No. 24, Bareng, Klojen, Malang',
                'latitude' => -7.96540000, 'longitude' => 112.62200000,
                'desa' => 'Bareng', 'kecamatan' => 'Klojen',
                'kabupaten' => 'Malang', 'provinsi' => 'Jawa Timur',
            ],
            [
                'nama' => 'Kopdes Jember Tengah',
                'alamat' => 'Jl. PB Sudirman No. 78, Kaliwates, Jember',
                'latitude' => -8.17250000, 'longitude' => 113.70090000,
                'desa' => 'Kaliwates', 'kecamatan' => 'Kaliwates',
                'kabupaten' => 'Jember', 'provinsi' => 'Jawa Timur',
            ],
            [
                'nama' => 'Kopdes Kediri Raya',
                'alamat' => 'Jl. Dhoho No. 62, Bandar Lor, Mojoroto, Kediri',
                'latitude' => -7.81880000, 'longitude' => 112.01680000,
                'desa' => 'Bandar Lor', 'kecamatan' => 'Mojoroto',
                'kabupaten' => 'Kediri', 'provinsi' => 'Jawa Timur',
            ],
            [
                'nama' => 'Kopdes Banyuwangi Timur',
                'alamat' => 'Jl. A. Yani No. 30, Penganjuran, Banyuwangi',
                'latitude' => -8.21670000, 'longitude' => 114.36890000,
                'desa' => 'Penganjuran', 'kecamatan' => 'Banyuwangi',
                'kabupaten' => 'Banyuwangi', 'provinsi' => 'Jawa Timur',
            ],

            // ── BALI ─────────────────────────────────────────────────────
            [
                'nama' => 'Kopdes Kuta',
                'alamat' => 'Jl. Pantai Kuta No. 8, Kuta, Badung, Bali',
                'latitude' => -8.72310000, 'longitude' => 115.17270000,
                'desa' => 'Kuta', 'kecamatan' => 'Kuta',
                'kabupaten' => 'Badung', 'provinsi' => 'Bali',
            ],
            [
                'nama' => 'Kopdes Ubud',
                'alamat' => 'Jl. Raya Ubud No. 24, Ubud, Gianyar, Bali',
                'latitude' => -8.50690000, 'longitude' => 115.26250000,
                'desa' => 'Ubud', 'kecamatan' => 'Ubud',
                'kabupaten' => 'Gianyar', 'provinsi' => 'Bali',
            ],
            [
                'nama' => 'Kopdes Denpasar Utara',
                'alamat' => 'Jl. Gatot Subroto No. 100, Tonja, Denpasar Utara',
                'latitude' => -8.63800000, 'longitude' => 115.22290000,
                'desa' => 'Tonja', 'kecamatan' => 'Denpasar Utara',
                'kabupaten' => 'Denpasar', 'provinsi' => 'Bali',
            ],

            // ── SUMATERA UTARA ───────────────────────────────────────────
            [
                'nama' => 'Kopdes Medan Baru',
                'alamat' => 'Jl. Dr. Mansyur No. 89, Padang Bulan, Medan Baru',
                'latitude' => 3.57680000, 'longitude' => 98.65830000,
                'desa' => 'Padang Bulan', 'kecamatan' => 'Medan Baru',
                'kabupaten' => 'Medan', 'provinsi' => 'Sumatera Utara',
            ],
            [
                'nama' => 'Kopdes Pematangsiantar',
                'alamat' => 'Jl. Merdeka No. 4, Siopat Suhu, Siantar Timur',
                'latitude' => 2.95940000, 'longitude' => 99.06990000,
                'desa' => 'Siopat Suhu', 'kecamatan' => 'Siantar Timur',
                'kabupaten' => 'Pematangsiantar', 'provinsi' => 'Sumatera Utara',
            ],
            [
                'nama' => 'Kopdes Binjai Selatan',
                'alamat' => 'Jl. Soekarno Hatta No. 22, Rambung Barat, Binjai Selatan',
                'latitude' => 3.59210000, 'longitude' => 98.48540000,
                'desa' => 'Rambung Barat', 'kecamatan' => 'Binjai Selatan',
                'kabupaten' => 'Binjai', 'provinsi' => 'Sumatera Utara',
            ],

            // ── SUMATERA BARAT ───────────────────────────────────────────
            [
                'nama' => 'Kopdes Padang Timur',
                'alamat' => 'Jl. Rasuna Said No. 17, Padang Timur, Padang',
                'latitude' => -0.94920000, 'longitude' => 100.36460000,
                'desa' => 'Padang Timur', 'kecamatan' => 'Padang Timur',
                'kabupaten' => 'Padang', 'provinsi' => 'Sumatera Barat',
            ],
            [
                'nama' => 'Kopdes Bukittinggi Atas',
                'alamat' => 'Jl. A. Yani No. 1, Aur Kuning, Aur Birugo Tigo Baleh',
                'latitude' => -0.30780000, 'longitude' => 100.36820000,
                'desa' => 'Aur Kuning', 'kecamatan' => 'Aur Birugo Tigo Baleh',
                'kabupaten' => 'Bukittinggi', 'provinsi' => 'Sumatera Barat',
            ],

            // ── RIAU ─────────────────────────────────────────────────────
            [
                'nama' => 'Kopdes Pekanbaru Kota',
                'alamat' => 'Jl. Jenderal Sudirman No. 150, Tangkerang Tengah, Pekanbaru',
                'latitude' => 0.50710000, 'longitude' => 101.43770000,
                'desa' => 'Tangkerang Tengah', 'kecamatan' => 'Marpoyan Damai',
                'kabupaten' => 'Pekanbaru', 'provinsi' => 'Riau',
            ],
            [
                'nama' => 'Kopdes Dumai Barat',
                'alamat' => 'Jl. Sudirman No. 33, Rimba Sekampung, Dumai Kota',
                'latitude' => 1.67210000, 'longitude' => 101.44740000,
                'desa' => 'Rimba Sekampung', 'kecamatan' => 'Dumai Kota',
                'kabupaten' => 'Dumai', 'provinsi' => 'Riau',
            ],

            // ── SUMATERA SELATAN ─────────────────────────────────────────
            [
                'nama' => 'Kopdes Palembang Ilir',
                'alamat' => 'Jl. Kapten A. Rivai No. 8, Ilir Barat I, Palembang',
                'latitude' => -2.99160000, 'longitude' => 104.75540000,
                'desa' => 'Ilir Barat I', 'kecamatan' => 'Ilir Barat I',
                'kabupaten' => 'Palembang', 'provinsi' => 'Sumatera Selatan',
            ],
            [
                'nama' => 'Kopdes Lubuklinggau Timur',
                'alamat' => 'Jl. Yos Sudarso No. 15, Lubuk Linggau Timur I',
                'latitude' => -3.29590000, 'longitude' => 102.86300000,
                'desa' => 'Lubuk Linggau', 'kecamatan' => 'Lubuk Linggau Timur I',
                'kabupaten' => 'Lubuklinggau', 'provinsi' => 'Sumatera Selatan',
            ],

            // ── LAMPUNG ──────────────────────────────────────────────────
            [
                'nama' => 'Kopdes Telukbetung',
                'alamat' => 'Jl. Ikan Tenggiri No. 10, Telukbetung Selatan, Bandar Lampung',
                'latitude' => -5.43370000, 'longitude' => 105.26880000,
                'desa' => 'Pesawahan', 'kecamatan' => 'Telukbetung Selatan',
                'kabupaten' => 'Bandar Lampung', 'provinsi' => 'Lampung',
            ],

            // ── KALIMANTAN BARAT ─────────────────────────────────────────
            [
                'nama' => 'Kopdes Pontianak Kota',
                'alamat' => 'Jl. Gajah Mada No. 201, Pontianak Kota',
                'latitude' => -0.02520000, 'longitude' => 109.33220000,
                'desa' => 'Mariana', 'kecamatan' => 'Pontianak Kota',
                'kabupaten' => 'Pontianak', 'provinsi' => 'Kalimantan Barat',
            ],

            // ── KALIMANTAN SELATAN ───────────────────────────────────────
            [
                'nama' => 'Kopdes Banjarmasin Tengah',
                'alamat' => 'Jl. Lambung Mangkurat No. 7, Banjarmasin Tengah',
                'latitude' => -3.32340000, 'longitude' => 114.59210000,
                'desa' => 'Kertak Hanyar', 'kecamatan' => 'Banjarmasin Tengah',
                'kabupaten' => 'Banjarmasin', 'provinsi' => 'Kalimantan Selatan',
            ],
            [
                'nama' => 'Kopdes Banjarbaru Utara',
                'alamat' => 'Jl. A. Yani KM 33, Loktabat Utara, Banjarbaru Utara',
                'latitude' => -3.44290000, 'longitude' => 114.83570000,
                'desa' => 'Loktabat Utara', 'kecamatan' => 'Banjarbaru Utara',
                'kabupaten' => 'Banjarbaru', 'provinsi' => 'Kalimantan Selatan',
            ],

            // ── KALIMANTAN TIMUR ─────────────────────────────────────────
            [
                'nama' => 'Kopdes Samarinda Ulu',
                'alamat' => 'Jl. Untung Suropati No. 5, Samarinda Ulu',
                'latitude' => -0.50150000, 'longitude' => 117.14910000,
                'desa' => 'Teluk Lerong Ulu', 'kecamatan' => 'Samarinda Ulu',
                'kabupaten' => 'Samarinda', 'provinsi' => 'Kalimantan Timur',
            ],
            [
                'nama' => 'Kopdes Balikpapan Barat',
                'alamat' => 'Jl. Jend. Sudirman No. 30, Klandasan Ulu, Balikpapan',
                'latitude' => -1.24310000, 'longitude' => 116.85360000,
                'desa' => 'Klandasan Ulu', 'kecamatan' => 'Balikpapan Barat',
                'kabupaten' => 'Balikpapan', 'provinsi' => 'Kalimantan Timur',
            ],

            // ── SULAWESI SELATAN ─────────────────────────────────────────
            [
                'nama' => 'Kopdes Makassar Selatan',
                'alamat' => 'Jl. Pengayoman No. 5, Panakkukang, Makassar',
                'latitude' => -5.15480000, 'longitude' => 119.43280000,
                'desa' => 'Panakkukang', 'kecamatan' => 'Panakkukang',
                'kabupaten' => 'Makassar', 'provinsi' => 'Sulawesi Selatan',
            ],
            [
                'nama' => 'Kopdes Gowa Makmur',
                'alamat' => 'Jl. Tumanurung No. 2, Sungguminasa, Somba Opu',
                'latitude' => -5.19640000, 'longitude' => 119.45730000,
                'desa' => 'Sungguminasa', 'kecamatan' => 'Somba Opu',
                'kabupaten' => 'Gowa', 'provinsi' => 'Sulawesi Selatan',
            ],
            [
                'nama' => 'Kopdes Parepare Utara',
                'alamat' => 'Jl. Bau Massepe No. 10, Ujung Baru, Parepare',
                'latitude' => -4.00830000, 'longitude' => 119.62340000,
                'desa' => 'Ujung Baru', 'kecamatan' => 'Soreang',
                'kabupaten' => 'Parepare', 'provinsi' => 'Sulawesi Selatan',
            ],

            // ── SULAWESI UTARA ───────────────────────────────────────────
            [
                'nama' => 'Kopdes Manado Kota',
                'alamat' => 'Jl. Sam Ratulangi No. 88, Wenang, Manado',
                'latitude' => 1.47990000, 'longitude' => 124.83640000,
                'desa' => 'Wenang Utara', 'kecamatan' => 'Wenang',
                'kabupaten' => 'Manado', 'provinsi' => 'Sulawesi Utara',
            ],

            // ── SULAWESI TENGAH ──────────────────────────────────────────
            [
                'nama' => 'Kopdes Palu Timur',
                'alamat' => 'Jl. Juanda No. 15, Besusu Timur, Palu Timur',
                'latitude' => -0.87830000, 'longitude' => 119.87810000,
                'desa' => 'Besusu Timur', 'kecamatan' => 'Palu Timur',
                'kabupaten' => 'Palu', 'provinsi' => 'Sulawesi Tengah',
            ],

            // ── NUSA TENGGARA BARAT ──────────────────────────────────────
            [
                'nama' => 'Kopdes Mataram Barat',
                'alamat' => 'Jl. Pejanggik No. 40, Cakranegara, Mataram',
                'latitude' => -8.58640000, 'longitude' => 116.09690000,
                'desa' => 'Cilinaya', 'kecamatan' => 'Cakranegara',
                'kabupaten' => 'Mataram', 'provinsi' => 'Nusa Tenggara Barat',
            ],
            [
                'nama' => 'Kopdes Sumbawa Besar',
                'alamat' => 'Jl. Garuda No. 5, Brang Bara, Sumbawa',
                'latitude' => -8.48870000, 'longitude' => 117.43070000,
                'desa' => 'Brang Bara', 'kecamatan' => 'Sumbawa',
                'kabupaten' => 'Sumbawa', 'provinsi' => 'Nusa Tenggara Barat',
            ],

            // ── NUSA TENGGARA TIMUR ──────────────────────────────────────
            [
                'nama' => 'Kopdes Kupang Tengah',
                'alamat' => 'Jl. El Tari No. 1, Oebobo, Kupang',
                'latitude' => -10.17100000, 'longitude' => 123.60680000,
                'desa' => 'Oebobo', 'kecamatan' => 'Oebobo',
                'kabupaten' => 'Kupang', 'provinsi' => 'Nusa Tenggara Timur',
            ],

            // ── MALUKU ───────────────────────────────────────────────────
            [
                'nama' => 'Kopdes Ambon Kota',
                'alamat' => 'Jl. A. Y. Patty No. 5, Amantelu, Sirimau, Ambon',
                'latitude' => -3.69590000, 'longitude' => 128.18180000,
                'desa' => 'Amantelu', 'kecamatan' => 'Sirimau',
                'kabupaten' => 'Ambon', 'provinsi' => 'Maluku',
            ],

            // ── PAPUA ─────────────────────────────────────────────────────
            [
                'nama' => 'Kopdes Jayapura Selatan',
                'alamat' => 'Jl. Sam Ratulangi No. 2, Entrop, Jayapura Selatan',
                'latitude' => -2.53790000, 'longitude' => 140.71820000,
                'desa' => 'Entrop', 'kecamatan' => 'Jayapura Selatan',
                'kabupaten' => 'Jayapura', 'provinsi' => 'Papua',
            ],
            [
                'nama' => 'Kopdes Merauke Timur',
                'alamat' => 'Jl. Raya Mandala No. 10, Merauke',
                'latitude' => -8.49730000, 'longitude' => 140.40170000,
                'desa' => 'Mandala', 'kecamatan' => 'Merauke',
                'kabupaten' => 'Merauke', 'provinsi' => 'Papua Selatan',
            ],

            // ── ACEH ─────────────────────────────────────────────────────
            [
                'nama' => 'Kopdes Banda Aceh Kota',
                'alamat' => 'Jl. Teuku Umar No. 2, Kuta Alam, Banda Aceh',
                'latitude' => 5.54830000, 'longitude' => 95.32380000,
                'desa' => 'Kuta Alam', 'kecamatan' => 'Kuta Alam',
                'kabupaten' => 'Banda Aceh', 'provinsi' => 'Aceh',
            ],
            [
                'nama' => 'Kopdes Lhokseumawe',
                'alamat' => 'Jl. Merdeka No. 1, Banda Sakti, Lhokseumawe',
                'latitude' => 5.17940000, 'longitude' => 97.14200000,
                'desa' => 'Banda Sakti', 'kecamatan' => 'Banda Sakti',
                'kabupaten' => 'Lhokseumawe', 'provinsi' => 'Aceh',
            ],

            // ── GORONTALO ────────────────────────────────────────────────
            [
                'nama' => 'Kopdes Gorontalo Utara',
                'alamat' => 'Jl. Pangeran Hidayat No. 7, Dulalowo, Kota Tengah, Gorontalo',
                'latitude' => 0.54290000, 'longitude' => 123.06230000,
                'desa' => 'Dulalowo', 'kecamatan' => 'Kota Tengah',
                'kabupaten' => 'Gorontalo', 'provinsi' => 'Gorontalo',
            ],

            // ── KEPULAUAN RIAU ───────────────────────────────────────────
            [
                'nama' => 'Kopdes Batam Center',
                'alamat' => 'Jl. Engku Putri No. 1, Belian, Batam Kota',
                'latitude' => 1.12340000, 'longitude' => 104.05730000,
                'desa' => 'Belian', 'kecamatan' => 'Batam Kota',
                'kabupaten' => 'Batam', 'provinsi' => 'Kepulauan Riau',
            ],
            [
                'nama' => 'Kopdes Tanjungpinang',
                'alamat' => 'Jl. Merdeka No. 25, Tanjungpinang Barat',
                'latitude' => 0.91920000, 'longitude' => 104.44580000,
                'desa' => 'Kampung Bugis', 'kecamatan' => 'Tanjungpinang Barat',
                'kabupaten' => 'Tanjungpinang', 'provinsi' => 'Kepulauan Riau',
            ],

            // ── BANGKA BELITUNG ──────────────────────────────────────────
            [
                'nama' => 'Kopdes Pangkalpinang',
                'alamat' => 'Jl. Jend. Sudirman No. 60, Pangkalbalam, Pangkalpinang',
                'latitude' => -2.12870000, 'longitude' => 106.11580000,
                'desa' => 'Pangkalbalam', 'kecamatan' => 'Pangkalbalam',
                'kabupaten' => 'Pangkalpinang', 'provinsi' => 'Bangka Belitung',
            ],

            // ── KALIMANTAN TENGAH ────────────────────────────────────────
            [
                'nama' => 'Kopdes Palangka Raya',
                'alamat' => 'Jl. Tjilik Riwut KM 1, Pahandut, Pahandut, Palangka Raya',
                'latitude' => -2.21070000, 'longitude' => 113.91310000,
                'desa' => 'Pahandut', 'kecamatan' => 'Pahandut',
                'kabupaten' => 'Palangka Raya', 'provinsi' => 'Kalimantan Tengah',
            ],

            // ── KALIMANTAN UTARA ─────────────────────────────────────────
            [
                'nama' => 'Kopdes Tarakan Tengah',
                'alamat' => 'Jl. Yos Sudarso No. 4, Selumit, Tarakan Tengah',
                'latitude' => 3.29730000, 'longitude' => 117.57440000,
                'desa' => 'Selumit', 'kecamatan' => 'Tarakan Tengah',
                'kabupaten' => 'Tarakan', 'provinsi' => 'Kalimantan Utara',
            ],
        ];

        foreach ($koperasi as $kop) {
            Kopdes::create($kop);
        }
    }
}
