@extends('layouts.app')
@section('title', 'Detail Karyawan')
@section('page-title', 'Detail Karyawan')
@section('breadcrumb', 'Manajemen › Data Karyawan › Detail')

@section('content')
<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;">

    {{-- Profile Sidebar --}}
    <div class="card" style="text-align:center;padding:24px;">
        @if($karyawan->foto_profil)
        <img src="{{ Storage::url($karyawan->foto_profil) }}" class="avatar avatar-lg" style="margin:0 auto 16px;">
        @else
        <div class="avatar avatar-lg" style="background:#fff0f0;color:#cc0000;margin:0 auto 16px;">
            {{ strtoupper(substr($karyawan->name,0,1)) }}
        </div>
        @endif

        <h3 style="font-size:16px;font-weight:700;color:#1a1a1a;margin-bottom:4px;">{{ $karyawan->name }}</h3>
        <div style="font-size:12px;color:#888;margin-bottom:12px;">{{ $karyawan->email }}</div>

        <div style="display:flex;gap:6px;justify-content:center;margin-bottom:16px;">
            <span class="badge badge-primary">{{ $karyawan->jabatanLabel() }}</span>
            <span class="badge {{ $karyawan->status=='aktif' ? 'badge-success' : ($karyawan->status=='cuti' ? 'badge-warning' : 'badge-danger') }}">
                {{ ucfirst($karyawan->status) }}
            </span>
        </div>

        <div style="text-align:left;border-top:1px solid #eee;padding-top:14px;font-size:12px;display:flex;flex-direction:column;gap:8px;">
            <div><span style="color:#888;">NIP:</span> <strong>{{ $karyawan->nip ?? '-' }}</strong></div>
            <div><span style="color:#888;">NIK:</span> <strong>{{ $karyawan->nik ?? '-' }}</strong></div>
            <div><span style="color:#888;">No. HP:</span> <strong>{{ $karyawan->no_hp ?? '-' }}</strong></div>
            <div><span style="color:#888;">Shift Default:</span> <strong>{{ $karyawan->shiftDefault?->nama_shift ?? 'Belum Diatur' }}</strong></div>
        </div>

        <div style="margin-top:20px;display:flex;gap:10px;">
            <a href="{{ route('karyawan.edit', $karyawan) }}" class="btn btn-secondary btn-sm" style="flex:1;justify-content:center;">Edit Profile</a>
        </div>
    </div>

    {{-- Detailed Info & History --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Detail Card --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Informasi Lengkap</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:13px;">
                    <div><span style="color:#888;display:block;font-size:11px;">Tempat, Tanggal Lahir</span> <strong>{{ $karyawan->tempat_lahir ?? '-' }}, {{ $karyawan->tanggal_lahir?->format('d F Y') ?? '-' }}</strong></div>
                    <div><span style="color:#888;display:block;font-size:11px;">Jenis Kelamin</span> <strong>{{ $karyawan->jenis_kelamin == 'L' ? 'Laki-laki' : ($karyawan->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</strong></div>
                    <div><span style="color:#888;display:block;font-size:11px;">Agama</span> <strong>{{ $karyawan->agama ?? '-' }}</strong></div>
                    <div><span style="color:#888;display:block;font-size:11px;">Terdaftar Sejak</span> <strong>{{ $karyawan->created_at->format('d M Y') }}</strong></div>
                    <div style="grid-column:span 2;"><span style="color:#888;display:block;font-size:11px;">Alamat</span> <strong>{{ $karyawan->alamat ?? '-' }}</strong></div>
                </div>
            </div>
        </div>

        {{-- Recent Attendance --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Riwayat Absensi Terakhir</span>
                <a href="{{ route('absensi.index', ['user_id' => $karyawan->id]) }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Pulang</th>
                            <th>Status</th>
                            <th>Keterlambatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawan->absensi as $abs)
                        <tr>
                            <td>{{ $abs->tanggal->format('d M Y') }}</td>
                            <td>{{ $abs->jam_masuk ? substr($abs->jam_masuk,0,5) : '-' }}</td>
                            <td>{{ $abs->jam_pulang ? substr($abs->jam_pulang,0,5) : '-' }}</td>
                            <td><span class="badge badge-{{ $abs->statusColor() }}">{{ $abs->statusLabel() }}</span></td>
                            <td>{{ $abs->keterlambatan_menit > 0 ? $abs->keterlambatan_menit . ' menit' : '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;padding:20px;color:#888;">Belum ada riwayat absensi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
