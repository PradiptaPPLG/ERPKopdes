@extends('layouts.app')
@section('title', 'Menu Panduan')
@section('page-title', 'Panduan & Standar Operasional Prosedur (SOP)')
@section('breadcrumb', 'Bantuan › Menu Panduan')

@section('content')
<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    {{-- SOP Alert Banner --}}
    <div style="background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%); border-left: 5px solid #cc0000; border-radius: 8px; padding: 20px; box-shadow: 0 4px 12px rgba(204, 0, 0, 0.05);">
        <div style="display: flex; align-items: start; gap: 16px;">
            <div style="width: 40px; height: 40px; background: #cc0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(204, 0, 0, 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 style="font-size: 15px; font-weight: 700; color: #990000; margin-bottom: 4px;">⚠️ ATURAN KEDISIPLINAN & KONSEKUENSI KETERLAMBATAN</h3>
                <p style="font-size: 13px; color: #555; line-height: 1.5; margin: 0;">
                    Sistem mendeteksi kehadiran Anda secara real-time. Jika Anda berstatus <strong>"Sangat Terlambat"</strong> (lebih dari 30 menit dari jam shift kerja Anda) sebanyak <strong>10 kali akumulatif dalam 1 bulan</strong>, maka sistem akan secara otomatis menerbitkan <strong>Surat Peringatan Pertama (SP1)</strong> dan dikenakan pemotongan insentif/transport harian sebesar 50%.
                </p>
            </div>
        </div>
    </div>

    {{-- Tailored Guide Based on Role --}}
    <div class="card">
        <div class="card-header" style="background: linear-gradient(90deg, #fcfcfc 0%, #f7f7f7 100%);">
            <span class="card-title">📖 Panduan Khusus: {{ auth()->user()->jabatanLabel() }}</span>
        </div>
        <div class="card-body" style="padding: 24px;">

            @if(auth()->user()->jabatan === 'admin')
                {{-- ──────────────── ADMIN GUIDE ──────────────── --}}
                <h4 style="font-size: 15px; font-weight: 700; color: #1a1a1a; margin-bottom: 12px;">Tugas & Tanggung Jawab Administrator:</h4>
                <ul style="padding-left: 20px; line-height: 1.8; font-size: 13.5px; color: #444; display: flex; flex-direction: column; gap: 8px;">
                    <li><strong>Kelola Data Karyawan:</strong> Menambahkan akun karyawan baru, mengedit data personal, serta menonaktifkan akun karyawan yang sudah tidak bertugas.</li>
                    <li><strong>Manajemen & Penjadwalan Shift:</strong> Menentukan jenis shift kerja beserta toleransi keterlambatan pada menu <em>Manajemen Shift</em>, dan mengatur jadwal bulanan di menu <em>Jadwal Shift</em>.</li>
                    <li><strong>Monitor Absensi & Verifikasi Maps:</strong> Melakukan pengawasan kehadiran harian melalui menu <em>Monitor Absensi</em>. Selidiki koordinat GPS peta (Leaflet Maps) pegawai untuk mendeteksi kecurangan (fake GPS/VPN) dan verifikasi/tolak tanda tangan digital mereka.</li>
                    <li><strong>Persetujuan Cuti/Izin:</strong> Menilai berkas pengajuan izin & cuti yang diajukan oleh pegawai.</li>
                    <li><strong>Ekspor Laporan Kehadiran:</strong> Melakukan rekapitulasi data absensi bulanan untuk penentuan penggajian.</li>
                </ul>

            @elseif(in_array(auth()->user()->jabatan, ['ketua', 'sekretaris']))
                {{-- ──────────────── KETUA & SEKRETARIS GUIDE ──────────────── --}}
                <h4 style="font-size: 15px; font-weight: 700; color: #1a1a1a; margin-bottom: 12px;">Tugas & Tanggung Jawab Pengawas / Pimpinan Koperasi:</h4>
                <ul style="padding-left: 20px; line-height: 1.8; font-size: 13.5px; color: #444; display: flex; flex-direction: column; gap: 8px;">
                    <li><strong>Persetujuan & Approval Cuti/Izin:</strong> Meninjau alasan ketidakhadiran pegawai pada menu <em>Izin & Cuti</em> dan memberikan keputusan (Setuju/Tolak).</li>
                    <li><strong>Monitoring Kehadiran:</strong> Memantau rekap absensi harian dan heatmap bulanan karyawan untuk memastikan kelancaran operasional koperasi.</li>
                    <li><strong>Evaluasi Kinerja:</strong> Menilai kedisiplinan dan produktivitas karyawan berdasarkan total durasi kerja dan jumlah keterlambatan.</li>
                </ul>

            @elseif(auth()->user()->jabatan === 'bendahara')
                {{-- ──────────────── BENDAHARA GUIDE ──────────────── --}}
                <h4 style="font-size: 15px; font-weight: 700; color: #1a1a1a; margin-bottom: 12px;">SOP (Standar Operasional Prosedur) Bendahara:</h4>
                <ol style="padding-left: 20px; line-height: 1.8; font-size: 13.5px; color: #444; display: flex; flex-direction: column; gap: 10px;">
                    <li><strong>Kehadiran Tepat Waktu:</strong> Hadir dan melakukan Absen Masuk tepat waktu sesuai dengan shift default yang terjadwal.</li>
                    <li><strong>Etika & Kerapian:</strong> Mengenakan pakaian dinas resmi koperasi dengan rapi, sopan, dan memakai papan nama/ID Card selama jam kerja.</li>
                    <li><strong>Fokus Keuangan & Pembukuan:</strong> Mengelola arus kas masuk-keluar secara teliti, serta memastikan pencatatan keuangan harian selesai sebelum melakukan Absen Pulang.</li>
                    <li><strong>Prosedur Absensi:</strong>
                        <ul style="padding-left: 16px; margin-top: 4px; list-style-type: circle; color: #666;">
                            <li>Pastikan GPS ponsel/komputer menyala untuk deteksi background lokasi.</li>
                            <li>Tanda tangani pad digital dengan jelas sesuai tanda tangan asli Anda.</li>
                        </ul>
                    </li>
                </ol>

            @elseif(auth()->user()->jabatan === 'kasir')
                {{-- ──────────────── KASIR GUIDE ──────────────── --}}
                <h4 style="font-size: 15px; font-weight: 700; color: #1a1a1a; margin-bottom: 12px;">SOP (Standar Operasional Prosedur) Kasir:</h4>
                <ol style="padding-left: 20px; line-height: 1.8; font-size: 13.5px; color: #444; display: flex; flex-direction: column; gap: 10px;">
                    <li><strong>Kehadiran Tepat Waktu:</strong> Wajib hadir 15 menit sebelum shift dimulai untuk melakukan serah terima kas dengan kasir shift sebelumnya.</li>
                    <li><strong>Pelayanan Ramah (SOP 3S):</strong> Selalu menerapkan <strong>Senyum, Sapa, dan Salam</strong> kepada setiap anggota koperasi atau pembeli yang bertransaksi di kasir.</li>
                    <li><strong>Penampilan:</strong> Menggunakan seragam kasir yang bersih, rapi, rambut ditata rapi/hijab rapi, dan menjaga kebersihan area meja kasir.</li>
                    <li><strong>Keamanan Transaksi:</strong> Dilarang meninggalkan meja kasir dalam keadaan kosong tanpa izin supervisor/admin.</li>
                    <li><strong>Prosedur Absensi:</strong> Melakukan Absen Masuk dan Absen Pulang tepat waktu dengan verifikasi GPS background dan tanda tangan digital.</li>
                </ol>

            @elseif(auth()->user()->jabatan === 'petugas_toko')
                {{-- ──────────────── PETUGAS TOKO GUIDE ──────────────── --}}
                <h4 style="font-size: 15px; font-weight: 700; color: #1a1a1a; margin-bottom: 12px;">SOP (Standar Operasional Prosedur) Petugas Toko:</h4>
                <ol style="padding-left: 20px; line-height: 1.8; font-size: 13.5px; color: #444; display: flex; flex-direction: column; gap: 10px;">
                    <li><strong>Kehadiran & Kerapian:</strong> Datang tepat waktu sesuai jadwal shift, berpakaian seragam rapi, bersih, dan menggunakan sepatu tertutup.</li>
                    <li><strong>Keramahtamahan:</strong> Bersikap proaktif membantu pembeli mencari barang dagangan dengan sikap yang sopan, ramah, dan ramah tamah.</li>
                    <li><strong>Manajemen Produk:</strong> Selalu menjaga kerapian pajangan barang (display) di rak toko, mengecek tanggal kedaluwarsa barang, dan menjaga kebersihan seluruh koridor toko.</li>
                    <li><strong>Prosedur Absensi:</strong> Melakukan absen masuk saat mulai bertugas dan absen pulang ketika shift selesai melalui portal absensi digital.</li>
                </ol>
            @endif

        </div>
    </div>

    {{-- Sidebar Items Explanation --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">🔗 Penjelasan Menu Navigasi Anda</span>
        </div>
        <div class="card-body" style="padding: 20px;">
            <div style="display: flex; flex-direction: column; gap: 16px;">
                
                {{-- Dashboard --}}
                <div style="display: flex; gap: 16px; align-items: start; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
                    <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #555;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <h5 style="font-size: 13.5px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px;">Dashboard</h5>
                        <p style="font-size: 12px; color: #666; margin: 0; line-height: 1.4;">
                            Halaman utama yang menyajikan ringkasan shift harian Anda, status jam kerja, serta kalender **Heatmap Kehadiran** bulanan (untuk pegawai) atau grafik total kehadiran (untuk admin).
                        </p>
                    </div>
                </div>

                {{-- Absen Hari Ini (Karyawan Only) --}}
                @if(!auth()->user()->isAdmin())
                <div style="display: flex; gap: 16px; align-items: start; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
                    <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #555;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h5 style="font-size: 13.5px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px;">Absen Hari Ini</h5>
                        <p style="font-size: 12px; color: #666; margin: 0; line-height: 1.4;">
                            Menu wajib harian untuk melakukan **Absen Masuk** dan **Absen Pulang** menggunakan geolokasi background dan tanda tangan digital.
                        </p>
                    </div>
                </div>
                @endif

                {{-- Monitor Absensi (Admin Only) --}}
                @if(auth()->user()->isAdmin())
                <div style="display: flex; gap: 16px; align-items: start; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
                    <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #555;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h5 style="font-size: 13.5px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px;">Monitor Absensi</h5>
                        <p style="font-size: 12px; color: #666; margin: 0; line-height: 1.4;">
                            Memantau seluruh log kehadiran karyawan, memeriksa letak peta absensi karyawan (Leaflet Maps), tanda tangan, serta memproses persetujuan kehadiran.
                        </p>
                    </div>
                </div>
                @endif

                {{-- Izin & Cuti --}}
                <div style="display: flex; gap: 16px; align-items: start; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
                    <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #555;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h5 style="font-size: 13.5px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px;">Izin & Cuti</h5>
                        <p style="font-size: 12px; color: #666; margin: 0; line-height: 1.4;">
                            Bagi pegawai: mengajukan permohonan berhalangan hadir (Sakit/Izin) dan mengunggah dokumen pendukung. Bagi pimpinan: memproses persetujuan (approve/reject).
                        </p>
                    </div>
                </div>

                {{-- Data Karyawan, Shift, Jadwal (Admin Only) --}}
                @if(auth()->user()->canApprove())
                <div style="display: flex; gap: 16px; align-items: start;">
                    <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #555;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h5 style="font-size: 13.5px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px;">Manajemen Data (Karyawan, Shift & Jadwal)</h5>
                        <p style="font-size: 12px; color: #666; margin: 0; line-height: 1.4;">
                            Rangkaian menu administratif khusus pimpinan untuk mengelola status karyawan, waktu jam kerja shift, serta pengaturan jadwal piket harian/bulanan.
                        </p>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

</div>
@endsection
