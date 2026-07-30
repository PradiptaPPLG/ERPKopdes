@extends('layouts.app')
@section('title', 'Laporan Kehadiran')
@section('page-title', 'Laporan & Heatmap Kehadiran')
@section('breadcrumb', 'Manajemen › Laporan Kehadiran')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div style="display:flex;flex-direction:column;gap:20px;">

    {{-- Filter Header Card --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Filter Laporan Bulanan</span>
        </div>
        <div class="card-body" style="padding:14px 20px;">
            <form method="GET" action="{{ route('laporan.index') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                <select name="bulan" class="form-control" style="width:160px;">
                    @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                    @endforeach
                </select>
                <select name="tahun" class="form-control" style="width:120px;">
                    @foreach(range(now()->year - 1, now()->year + 1) as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <select name="user_id" class="form-control" style="width:200px;">
                    <option value="">Semua Karyawan</option>
                    @foreach($karyawan as $k)
                    <option value="{{ $k->id }}" {{ $userId == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Tampilkan Laporan</button>
                <a href="{{ route('laporan.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </form>
        </div>
    </div>

    {{-- Heatmap Calendar Card --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Heatmap Kehadiran: {{ $bulanNama }}</span>
        </div>
        <div class="card-body">
            <div style="max-width:700px;margin:0 auto;">
                <div class="heatmap-grid" style="margin-bottom:8px;">
                    @foreach(['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $d)
                    <div class="heatmap-day heatmap-header" style="font-size:11px;padding:4px 0;">{{ $d }}</div>
                    @endforeach
                </div>

                @php
                    $firstDay = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);
                    $startOffset = $firstDay->dayOfWeek;
                    $totalCells = ceil(($startOffset + $daysInMonth) / 7) * 7;
                    $totalEmployees = count($karyawan);
                @endphp

                <div class="heatmap-grid" style="gap:6px;">
                    @for($i = 0; $i < $startOffset; $i++)
                    <div class="heatmap-day heatmap-empty"></div>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateKey = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
                        $d = $heatmapData[$dateKey] ?? null;
                        $date = \Carbon\Carbon::parse($dateKey);
                        $isWeekend = $date->isWeekend();
                        $isFuture = $date->isFuture();

                        if ($isWeekend || $isFuture) {
                            $cls = 'heatmap-0';
                        } elseif (!$d) {
                            $cls = 'heatmap-danger';
                        } else {
                            $sangat = (int)($d->sangat_terlambat ?? 0);
                            $pct    = $totalEmployees > 0 ? ($d->hadir / $totalEmployees) : 0;

                            if ($sangat > 0 && $pct >= 0.9) {
                                $cls = 'heatmap-warn';   // mostly hadir but ada sangat terlambat
                            } elseif ($sangat > 0) {
                                $cls = 'heatmap-danger';
                            } elseif ($pct >= 0.9)      { $cls = 'heatmap-5'; }
                            elseif ($pct >= 0.75)       { $cls = 'heatmap-4'; }
                            elseif ($pct >= 0.6)        { $cls = 'heatmap-3'; }
                            elseif ($pct >= 0.4)        { $cls = 'heatmap-2'; }
                            elseif ($pct > 0)           { $cls = 'heatmap-1'; }
                            else                        { $cls = 'heatmap-danger'; }
                        }

                        $sangat = (int)($d->sangat_terlambat ?? 0);
                        $info = $d
                            ? "Hadir: {$d->hadir}, Terlambat: {$d->terlambat}, Sangat Terlambat: {$sangat}, Izin: {$d->izin}, Alpa: {$d->alpa}"
                            : ($isWeekend ? 'Libur Weekend' : 'Tidak Ada Data');
                    @endphp
                    <div class="heatmap-day {{ $cls }}" style="height:44px;font-size:12px;border-radius:6px;" title="Tgl {{ $day }}: {{ $info }}">
                        <div style="display:flex;flex-direction:column;align-items:center;">
                            <span style="font-weight:700;">{{ $day }}</span>
                            @if($d && !$isWeekend && !$isFuture)
                            <span style="font-size:9px;opacity:0.8;">{{ $d->hadir }} H</span>
                            @endif
                        </div>
                    </div>
                    @endfor

                    @for($i = $startOffset + $daysInMonth; $i < $totalCells; $i++)
                    <div class="heatmap-day heatmap-empty"></div>
                    @endfor
                </div>

                <div style="display:flex;gap:10px;justify-content:center;margin-top:16px;font-size:11px;color:#666;flex-wrap:wrap;">
                    <span style="display:flex;align-items:center;gap:4px;"><span style="width:14px;height:14px;border-radius:3px;background:#f3f4f6;"></span> Weekend</span>
                    <span style="display:flex;align-items:center;gap:4px;"><span style="width:14px;height:14px;border-radius:3px;background:#fef9c3;"></span> ⚠ Terlambat</span>
                    <span style="display:flex;align-items:center;gap:4px;"><span style="width:14px;height:14px;border-radius:3px;background:#fee2e2;"></span> 🔴 Sangat Terlambat / 0%</span>
                    <span style="display:flex;align-items:center;gap:4px;"><span style="width:14px;height:14px;border-radius:3px;background:#bbf7d0;"></span> &lt; 60%</span>
                    <span style="display:flex;align-items:center;gap:4px;"><span style="width:14px;height:14px;border-radius:3px;background:#22c55e;"></span> &gt; 90% Sangat Baik</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Employee Attendance Summary Table --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Ringkasan Kehadiran per Karyawan ({{ $bulanNama }})</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Hadir</th>
                        <th>Terlambat <span style="color:#d97706;">(1-30 mnt)</span></th>
                        <th>Sangat Terlambat <span style="color:#dc2626;">(31+ mnt)</span></th>
                        <th>Izin / Cuti</th>
                        <th>Sakit</th>
                        <th>Alpa</th>
                        <th>Total Terlambat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ringkasan as $r)
                    <tr>
                        <td style="font-weight:600;">
                            {{ $r->user->name }}
                            <div style="font-size:10px;color:#888;">{{ $r->user->jabatanLabel() }}</div>
                        </td>
                        <td><span class="badge badge-success">{{ $r->hadir }} hari</span></td>
                        <td>
                            <span class="badge badge-warning">{{ $r->terlambat }} kali</span>
                        </td>
                        <td>
                            @php $sangatT = $r->sangat_terlambat ?? 0; @endphp
                            @if($sangatT > 0)
                            <span class="badge badge-danger">{{ $sangatT }} kali</span>
                            @else
                            <span style="color:#888;">0</span>
                            @endif
                        </td>
                        <td>{{ $r->izin }} hari</td>
                        <td>{{ $r->sakit }} hari</td>
                        <td><span class="badge badge-danger">{{ $r->alpa }} hari</span></td>
                        <td>{{ $r->total_terlambat_menit }} menit</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center;padding:24px;color:#888;">Tidak ada data ringkasan untuk periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
