@extends('layouts.app')
@section('title', 'Jadwal Shift')
@section('page-title', 'Penjadwalan Shift Karyawan')
@section('breadcrumb', 'Manajemen › Jadwal Shift')

@section('content')
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <span class="card-title">Matriks Jadwal Bulanan</span>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('jadwal.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buat Jadwal Shift
            </a>
        </div>
    </div>

    {{-- Filter Month & Year --}}
    <div style="padding:14px 20px;border-bottom:1px solid #e5e5e5;background:#fafafa;">
        <form method="GET" action="{{ route('jadwal.index') }}" style="display:flex;gap:10px;align-items:center;">
            <label style="font-weight:600;font-size:12px;color:#555;">Pilih Periode:</label>
            <select name="bulan" class="form-control" style="width:140px;">
                @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
                @endforeach
            </select>
            <select name="tahun" class="form-control" style="width:110px;">
                @foreach(range(now()->year - 1, now()->year + 1) as $y)
                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Tampilkan</button>
        </form>
    </div>

    {{-- Matrix Calendar Table --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="position:sticky;left:0;background:#fafafa;z-index:2;min-width:180px;">Karyawan</th>
                    @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dt = \Carbon\Carbon::createFromDate($tahun, $bulan, $day);
                        $isWeekend = $dt->isWeekend();
                    @endphp
                    <th style="text-align:center;min-width:42px;padding:6px 2px;{{ $isWeekend ? 'background:#fee2e2;color:#991b1b;' : '' }}">
                        <div style="font-size:10px;font-weight:700;">{{ substr($dt->translatedFormat('D'),0,3) }}</div>
                        <div style="font-size:12px;">{{ $day }}</div>
                    </th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @forelse($karyawan as $k)
                <tr>
                    <td style="position:sticky;left:0;background:#fff;z-index:1;font-weight:600;box-shadow:2px 0 5px rgba(0,0,0,0.05);">
                        <div>{{ $k->name }}</div>
                        <div style="font-size:10px;color:#888;">{{ $k->jabatanLabel() }}</div>
                    </td>

                    @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateStr = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
                        $userJadwals = $jadwals[$k->id] ?? collect();
                        $j = $userJadwals->firstWhere('tanggal', \Carbon\Carbon::parse($dateStr));
                    @endphp
                    <td style="text-align:center;padding:4px 2px;">
                        @if($j)
                        <span title="{{ $j->shift->nama_shift }} ({{ $j->shift->jam_mulai_format }}-{{ $j->shift->jam_selesai_format }})"
                              style="display:inline-block;width:28px;height:24px;line-height:24px;border-radius:4px;font-size:10px;font-weight:700;color:#fff;background:{{ $j->shift->kode_warna }};">
                            {{ strtoupper(substr($j->shift->nama_shift, 0, 1)) }}
                        </span>
                        @else
                        <span style="color:#ccc;font-size:11px;">-</span>
                        @endif
                    </td>
                    @endfor
                </tr>
                @empty
                <tr><td colspan="{{ $daysInMonth + 1 }}" style="text-align:center;padding:24px;color:#888;">Belum ada karyawan aktif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:14px 20px;background:#fafafa;border-top:1px solid #e5e5e5;display:flex;gap:16px;font-size:11px;color:#555;">
        <strong>Keterangan Shift:</strong>
        <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:12px;border-radius:3px;background:#10b981;"></span> P = Pagi</span>
        <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:12px;border-radius:3px;background:#f59e0b;"></span> S = Siang</span>
        <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:12px;border-radius:3px;background:#ef4444;"></span> F = Full</span>
    </div>
</div>
@endsection
