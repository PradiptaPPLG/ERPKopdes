@extends('layouts.app')
@section('title', 'Rekap Absensi')
@section('page-title', 'Rekap Absensi Karyawan')
@section('breadcrumb', 'Kehadiran › Rekap Absensi')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">Data Absensi</span>
    </div>

    {{-- Filter Bar --}}
    <div style="padding:14px 20px;border-bottom:1px solid #e5e5e5;background:#fafafa;">
        <form method="GET" action="{{ route('absensi.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <input type="date" name="tanggal" value="{{ request('tanggal', today()->format('Y-m-d')) }}" class="form-control" style="width:160px;">

            @if(auth()->user()->canApprove())
            <select name="user_id" class="form-control" style="width:180px;">
                <option value="">Semua Karyawan</option>
                @foreach($karyawan as $k)
                <option value="{{ $k->id }}" {{ request('user_id')==$k->id ? 'selected':'' }}>{{ $k->name }}</option>
                @endforeach
            </select>
            @endif

            <select name="status" class="form-control" style="width:160px;">
                <option value="">Semua Status</option>
                <option value="hadir" {{ request('status')=='hadir'?'selected':'' }}>Hadir</option>
                <option value="terlambat" {{ request('status')=='terlambat'?'selected':'' }}>🟠 Terlambat (1-30 mnt)</option>
                <option value="sangat_terlambat" {{ request('status')=='sangat_terlambat'?'selected':'' }}>🔴 Sangat Terlambat (31+ mnt)</option>
                <option value="izin" {{ request('status')=='izin'?'selected':'' }}>Izin</option>
                <option value="sakit" {{ request('status')=='sakit'?'selected':'' }}>Sakit</option>
                <option value="alpa" {{ request('status')=='alpa'?'selected':'' }}>Alpa</option>
            </select>


            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('absensi.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Karyawan</th>
                    <th>Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Lokasi Masuk</th>
                    <th>Status</th>
                    <th>Keterlambatan</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensi as $abs)
                <tr>
                    <td style="font-size:12px;white-space:nowrap;">{{ $abs->tanggal->format('d/m/Y') }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $abs->user->name }}</div>
                        <div style="font-size:11px;color:#888;">{{ $abs->user->jabatanLabel() }}</div>
                    </td>
                    <td>
                        @if($abs->jadwal?->shift)
                        <span style="display:inline-block;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:700;background:{{ $abs->jadwal->shift->kode_warna }}22;color:{{ $abs->jadwal->shift->kode_warna }};">
                            {{ $abs->jadwal->shift->nama_shift }}
                        </span>
                        @else - @endif
                    </td>
                    <td style="font-weight:600;">{{ $abs->jam_masuk ? substr($abs->jam_masuk,0,5) : '-' }}</td>
                    <td style="color:#666;">{{ $abs->jam_pulang ? substr($abs->jam_pulang,0,5) : '-' }}</td>
                    <td style="font-size:11px;color:#666;max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $abs->lokasi_masuk ?? '-' }}
                    </td>
                    <td><span class="badge badge-{{ $abs->statusColor() }}">{{ $abs->statusLabel() }}</span></td>
                    <td style="font-size:12px;">{{ $abs->keterlambatan_menit > 0 ? $abs->keterlambatan_menit . ' mnt' : '-' }}</td>
                    <td style="text-align:center;">
                        <a href="{{ route('absensi.show', $abs) }}" class="btn btn-secondary btn-xs">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;padding:28px;color:#888;">Tidak ada data absensi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($absensi->hasPages())
    <div style="padding:14px 20px;border-top:1px solid #e5e5e5;">
        {{ $absensi->links() }}
    </div>
    @endif
</div>
@endsection
