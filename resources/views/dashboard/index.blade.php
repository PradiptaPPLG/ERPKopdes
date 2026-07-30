@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Selamat datang, ' . auth()->user()->name)

@section('content')

{{-- ── Greeting + Date ─────────────────────────────────────── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-size:18px;font-weight:700;color:#1a1a1a;">
            Selamat Datang, {{ auth()->user()->name }} 👋
        </h2>
        <p style="font-size:13px;color:#888;margin-top:2px;">
            {{ $today->translatedFormat('l, d F Y') }} &mdash; {{ auth()->user()->jabatanLabel() }}
        </p>
    </div>

    {{-- Admin: monitor buttons. Staff: absen button --}}
    @if(auth()->user()->isAdmin())
    <div style="display:flex;gap:10px;">
        <a href="{{ route('absensi.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Monitor Absensi
        </a>
        <a href="{{ route('laporan.index') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Lihat Laporan
        </a>
    </div>
    @else
    <a href="{{ route('absensi.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        Absen Sekarang
    </a>
    @endif
</div>

{{-- ── My attendance status today ──────────────────────────── --}}
@if($myJadwal)
<div class="card" style="margin-bottom:20px;border-left:4px solid {{ $myJadwal->shift->kode_warna }};padding:16px 20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:40px;height:40px;border-radius:8px;background:{{ $myJadwal->shift->kode_warna }}22;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="{{ $myJadwal->shift->kode_warna }}" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div style="font-size:13px;font-weight:700;color:#1a1a1a;">Shift Hari Ini: {{ $myJadwal->shift->nama_shift }}</div>
                <div style="font-size:12px;color:#666;">
                    {{ substr($myJadwal->shift->jam_mulai,0,5) }} – {{ substr($myJadwal->shift->jam_selesai,0,5) }} WIB
                    &bull; Toleransi {{ $myJadwal->shift->toleransi_keterlambatan_menit }} menit
                </div>
            </div>
        </div>
        <div style="display:flex;gap:16px;flex-wrap:wrap;">
            <div style="text-align:center;">
                <div style="font-size:11px;color:#888;font-weight:600;text-transform:uppercase;">Masuk</div>
                <div style="font-size:16px;font-weight:700;color:{{ $myAbsensi?->jam_masuk ? '#16a34a' : '#d97706' }};">
                    {{ $myAbsensi?->jam_masuk ? substr($myAbsensi->jam_masuk,0,5) : '--:--' }}
                </div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:11px;color:#888;font-weight:600;text-transform:uppercase;">Pulang</div>
                <div style="font-size:16px;font-weight:700;color:{{ $myAbsensi?->jam_pulang ? '#16a34a' : '#d97706' }};">
                    {{ $myAbsensi?->jam_pulang ? substr($myAbsensi->jam_pulang,0,5) : '--:--' }}
                </div>
            </div>
            @if($myAbsensi)
            <div style="text-align:center;">
                <div style="font-size:11px;color:#888;font-weight:600;text-transform:uppercase;">Status</div>
                <span class="badge badge-{{ $myAbsensi->statusColor() }}" style="margin-top:2px;">
                    {{ $myAbsensi->statusLabel() }}
                </span>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

{{-- ── Summary Cards ───────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px;">

    <div class="stat-card">
        <div class="stat-icon" style="background:#fff0f0;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="#cc0000" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
        <div>
            <div class="stat-value">{{ $totalKaryawan }}</div>
            <div class="stat-label">Total Karyawan</div>
            <div class="stat-sub">Status aktif & cuti</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#16a34a;">{{ $hadirHariIni }}</div>
            <div class="stat-label">Hadir Hari Ini</div>
            <div class="stat-sub">Dari {{ $totalKaryawan }} karyawan</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#fffbeb;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#d97706;">{{ $terlambatHariIni }}</div>
            <div class="stat-label">Terlambat</div>
            <div class="stat-sub">
                <span style="color:#d97706;">{{ $terlambatHariIni - $sangatTerlambatHariIni }}</span> ringan
                &bull; <span style="color:#dc2626;">{{ $sangatTerlambatHariIni }}</span> sangat
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#eff6ff;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="#1d4ed8" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#1d4ed8;">{{ $izinPending }}</div>
            <div class="stat-label">Izin Pending</div>
            <div class="stat-sub">Menunggu approval</div>
        </div>
    </div>

</div>

{{-- ── Two column: Absensi list + Heatmap ─────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;margin-bottom:20px;">

    {{-- Absensi hari ini --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Absensi Hari Ini</span>
            <a href="{{ route('absensi.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Shift</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiHariIni as $abs)
                    <tr>
                        <td>
                            <div style="font-weight:600;color:#1a1a1a;">{{ $abs->user->name }}</div>
                            <div style="font-size:11px;color:#888;">{{ $abs->user->jabatanLabel() }}</div>
                        </td>
                        <td>
                            @if($abs->jadwal?->shift)
                            <span style="display:inline-block;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;background:{{ $abs->jadwal->shift->kode_warna }}22;color:{{ $abs->jadwal->shift->kode_warna }};">
                                {{ $abs->jadwal->shift->nama_shift }}
                            </span>
                            @else —@endif
                        </td>
                        <td style="font-weight:600;">{{ $abs->jam_masuk ? substr($abs->jam_masuk,0,5) : '—' }}</td>
                        <td style="color:#666;">{{ $abs->jam_pulang ? substr($abs->jam_pulang,0,5) : '—' }}</td>
                        <td><span class="badge badge-{{ $abs->statusColor() }}">{{ $abs->statusLabel() }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:28px;color:#888;">Belum ada data absensi hari ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Heatmap bulan ini --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Kehadiran Bulan Ini</span>
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary btn-sm">Detail</a>
        </div>
        <div class="card-body" style="padding:16px;">
            {{-- Day headers --}}
            <div class="heatmap-grid" style="margin-bottom:4px;">
                @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $d)
                <div class="heatmap-day heatmap-header">{{ $d }}</div>
                @endforeach
            </div>

            @php
                $firstDay = \Carbon\Carbon::createFromDate($year, $month, 1);
                $startOffset = $firstDay->dayOfWeek; // 0=Sun
                $daysInMonth = $firstDay->daysInMonth;
                $totalCells = ceil(($startOffset + $daysInMonth) / 7) * 7;
            @endphp

            <div class="heatmap-grid">
                {{-- Empty cells before month start --}}
                @for($i = 0; $i < $startOffset; $i++)
                <div class="heatmap-day heatmap-empty"></div>
                @endfor

                {{-- Day cells --}}
                @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $d = $heatmapData[$dateKey] ?? null;
                    $date = \Carbon\Carbon::parse($dateKey);
                    $isWeekend = $date->isWeekend();
                    $isFuture = $date->isFuture();
                    $isToday = $date->isToday();

                    if ($isWeekend || $isFuture) {
                        $cls = 'heatmap-0';
                    } elseif (!$d) {
                        $cls = 'heatmap-danger';
                    } else {
                        $sangat = (int)($d->sangat_terlambat ?? 0);
                        $pct    = $totalKaryawan > 0 ? ($d->hadir / $totalKaryawan) : 0;

                        // If any sangat_terlambat → red override on otherwise good days
                        if ($sangat > 0 && $pct >= 0.9) {
                            $cls = 'heatmap-warn'; // mostly hadir but ada yg sangat terlambat
                        } elseif ($sangat > 0) {
                            $cls = 'heatmap-danger';
                        } elseif ($pct >= 0.9) {
                            $cls = 'heatmap-5';
                        } elseif ($pct >= 0.75) {
                            $cls = 'heatmap-4';
                        } elseif ($pct >= 0.6) {
                            $cls = 'heatmap-3';
                        } elseif ($pct >= 0.4) {
                            $cls = 'heatmap-2';
                        } elseif ($pct > 0) {
                            $cls = 'heatmap-1';
                        } else {
                            $cls = 'heatmap-danger';
                        }
                    }

                    $sangat = (int)($d->sangat_terlambat ?? 0);
                    $title  = $d
                        ? "{$d->hadir} hadir, {$d->terlambat} terlambat, {$sangat} sangat terlambat, {$d->alpa} alpa"
                        : ($isWeekend ? 'Libur' : 'Tidak ada data');
                @endphp
                <div class="heatmap-day {{ $cls }}"
                     style="{{ $isToday ? 'outline:2px solid #cc0000;outline-offset:-2px;' : '' }}"
                     title="{{ $day }} — {{ $title }}">
                    {{ $day }}
                </div>
                @endfor

                {{-- Padding cells --}}
                @for($i = $startOffset + $daysInMonth; $i < $totalCells; $i++)
                <div class="heatmap-day heatmap-empty"></div>
                @endfor
            </div>

            {{-- Legend --}}
            <div style="display:flex;gap:6px;margin-top:12px;flex-wrap:wrap;font-size:10px;color:#888;">
                <div style="display:flex;align-items:center;gap:3px;"><div style="width:10px;height:10px;border-radius:2px;background:#f3f4f6;"></div> Libur</div>
                <div style="display:flex;align-items:center;gap:3px;"><div style="width:10px;height:10px;border-radius:2px;background:#bbf7d0;"></div> &lt;60%</div>
                <div style="display:flex;align-items:center;gap:3px;"><div style="width:10px;height:10px;border-radius:2px;background:#22c55e;"></div> &gt;90%</div>
                <div style="display:flex;align-items:center;gap:3px;"><div style="width:10px;height:10px;border-radius:2px;background:#fef9c3;"></div> ⚠ Terlambat</div>
                <div style="display:flex;align-items:center;gap:3px;"><div style="width:10px;height:10px;border-radius:2px;background:#fee2e2;"></div> 🔴 Sangat Terlambat/Kosong</div>
            </div>
        </div>
    </div>

</div>

{{-- ── Izin Pending ────────────────────────────────────────── --}}
@if(auth()->user()->canApprove() && $izinTerbaru->count())
<div class="card">
    <div class="card-header">
        <span class="card-title">Permohonan Izin Menunggu Persetujuan</span>
        <a href="{{ route('izin.index', ['status'=>'pending']) }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Karyawan</th><th>Jenis</th><th>Tanggal</th><th>Durasi</th><th>Alasan</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($izinTerbaru as $izin)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $izin->user->name }}</div>
                        <div style="font-size:11px;color:#888;">{{ $izin->user->jabatanLabel() }}</div>
                    </td>
                    <td><span class="badge badge-info">{{ $izin->jenisLabel() }}</span></td>
                    <td style="font-size:12px;">
                        {{ $izin->tanggal_mulai->format('d M') }}
                        @if($izin->tanggal_mulai != $izin->tanggal_selesai)
                        — {{ $izin->tanggal_selesai->format('d M Y') }}
                        @else
                        {{ $izin->tanggal_mulai->format('Y') }}
                        @endif
                    </td>
                    <td>{{ $izin->jumlahHari() }} hari</td>
                    <td style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#666;">{{ $izin->alasan }}</td>
                    <td>
                        <a href="{{ route('izin.show', $izin) }}" class="btn btn-secondary btn-xs">Review</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
