@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Selamat datang, ' . auth()->user()->name)

@section('content')

{{-- ── Greeting + Date ─────────────────────────────────────── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-size:18px;font-weight:700;color:#1a1a1a;">
            Selamat Datang, {{ auth()->user()->name }}!
        </h2>
        <p style="font-size:13px;color:#888;margin-top:2px;">
            {{ $today->translatedFormat('l, d F Y') }} - 
            @if($isBranchManager)
                Manager Kopdes {{ $managedKopdes->nama }}
            @else
                {{ auth()->user()->jabatanLabel() }}
            @endif
        </p>
    </div>

    {{-- Admin: monitor buttons. Staff: absen button --}}
    @if(auth()->user()->isAdmin() || $isBranchManager)
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

{{-- ── My attendance status today (non-admin / non-manager only) ─────────── --}}
@if(!auth()->user()->isAdmin() && !$isBranchManager && $myJadwal)
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
            <div class="stat-sub">{{ $isBranchManager ? 'Cabang ' . $managedKopdes->nama : 'Status aktif & cuti' }}</div>
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

@if(auth()->user()->isAdmin() || $isBranchManager)
    {{-- Leaflet CSS & JS, Chart.js --}}
    @if(auth()->user()->isAdmin())
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if(auth()->user()->isAdmin())
    {{-- Peta Analitis Grid --}}
    <div style="font-weight:700;font-size:15px;color:#cc0000;margin:24px 0 12px;display:flex;align-items:center;gap:6px;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
        </svg>
        <span>Pemetaan Wilayah & Koperasi Nasional</span>
    </div>
    
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Peta Sebaran Karyawan (Titik Kopdes)</span>
            </div>
            <div class="card-body" style="padding:16px;">
                <div id="map-sebaran-titik" style="height:320px;border-radius:6px;border:1px solid #ddd;z-index:1;"></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Peta Densitas Regional (Zona Provinsi)</span>
            </div>
            <div class="card-body" style="padding:16px;">
                <div id="map-sebaran-regional" style="height:320px;border-radius:6px;border:1px solid #ddd;z-index:1;"></div>
            </div>
        </div>
    </div>
    @endif

    {{-- Grafik Analitis Grid --}}
    <div style="font-weight:700;font-size:15px;color:#cc0000;margin:24px 0 12px;display:flex;align-items:center;gap:6px;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
        </svg>
        <span>Dashboard Analitik &amp; Statistik {{ $isBranchManager ? 'Cabang ' . $managedKopdes->nama : 'Nasional' }}</span>
    </div>

    @if(auth()->user()->isAdmin())
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Karyawan per Provinsi</span>
            </div>
            <div class="card-body" style="padding:16px;min-height:280px;display:flex;align-items:center;justify-content:center;">
                <canvas id="chart-provinsi" style="max-height:260px;width:100%;"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Distribusi Karyawan per Kopdes</span>
            </div>
            <div class="card-body" style="padding:16px;min-height:280px;display:flex;align-items:center;justify-content:center;">
                <canvas id="chart-kopdes" style="max-height:260px;width:100%;"></canvas>
            </div>
        </div>
    </div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Top 5 Karyawan Paling Aktif (Hadir Tepat Waktu Bulan Ini)</span>
            </div>
            <div class="card-body" style="padding:16px;min-height:280px;display:flex;align-items:center;justify-content:center;">
                <canvas id="chart-aktif" style="max-height:260px;width:100%;"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Status Kehadiran Karyawan Bulan Ini</span>
            </div>
            <div class="card-body" style="padding:16px;min-height:280px;display:flex;align-items:center;justify-content:center;">
                <canvas id="chart-kehadiran" style="max-height:260px;width:100%;"></canvas>
            </div>
        </div>
    </div>
@endif

{{-- ── Two column: Absensi list + Heatmap ─────────────────── --}}
{{-- Admin/Manager: full width (no heatmap). Karyawan: 2-column with heatmap --}}
<div style="display:grid;grid-template-columns:{{ (auth()->user()->isAdmin() || $isBranchManager) ? '1fr' : '1fr 340px' }};gap:20px;margin-bottom:20px;">

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
                            @else -@endif
                        </td>
                        <td style="font-weight:600;">{{ $abs->jam_masuk ? substr($abs->jam_masuk,0,5) : '-' }}</td>
                        <td style="color:#666;">{{ $abs->jam_pulang ? substr($abs->jam_pulang,0,5) : '-' }}</td>
                        <td><span class="badge badge-{{ $abs->statusColor() }}">{{ $abs->statusLabel() }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:28px;color:#888;">Belum ada data absensi hari ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Heatmap bulan ini: hanya untuk karyawan (bukan admin) --}}
    @if(!auth()->user()->isAdmin())
    <div class="card">
        <div class="card-header">
            <span class="card-title">Kehadiran Bulan Ini</span>
            <a href="{{ route('absensi.index') }}" class="btn btn-secondary btn-sm">Detail</a>
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

                    // Prioritas: jika ada data absensi → tampilkan status nyata
                    // Weekend/future tanpa data absensi → abu-abu (libur/belum)
                    if ($d) {
                        $sangat = (int)($d->sangat_terlambat ?? 0);
                        $hadir  = (int)($d->hadir ?? 0);

                        if ($sangat > 0) {
                            $cls = 'heatmap-danger'; // Sangat Terlambat -> Merah
                        } elseif ($hadir > 0 && (int)($d->terlambat ?? 0) > 0) {
                            $cls = 'heatmap-warn';   // Terlambat Ringan -> Kuning
                        } elseif ($hadir > 0) {
                            $cls = 'heatmap-5';      // Hadir tepat waktu -> Hijau
                        } else {
                            $cls = 'heatmap-danger'; // Tidak hadir -> Merah
                        }
                    } elseif ($isFuture) {
                        $cls = 'heatmap-0';          // belum terjadi
                    } elseif ($isWeekend) {
                        $cls = 'heatmap-0';          // libur tanpa data
                    } else {
                        $cls = 'heatmap-danger';     // hari kerja tapi kosong
                    }

                    $sangat = (int)($d->sangat_terlambat ?? 0);
                    $title  = $d
                        ? (($d->hadir > 0 ? 'Hadir' : 'Tidak hadir')
                            . ($d->terlambat > 0 ? ', Terlambat' : '')
                            . ($sangat > 0 ? ', Sangat Terlambat' : ''))
                        : ($isWeekend ? 'Libur' : ($isFuture ? 'Belum terjadi' : 'Tidak ada data'));
                @endphp
                <div class="heatmap-day {{ $cls }}"
                     style="{{ $isToday ? 'outline:2px solid #cc0000;outline-offset:-2px;' : '' }}"
                     title="{{ $day }} - {{ $title }}">
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
                <div style="display:flex;align-items:center;gap:3px;"><div style="width:10px;height:10px;border-radius:2px;background:#22c55e;"></div> Hadir</div>
                <div style="display:flex;align-items:center;gap:3px;"><div style="width:10px;height:10px;border-radius:2px;background:#fef9c3;"></div> ⚠ Terlambat</div>
                <div style="display:flex;align-items:center;gap:3px;"><div style="width:10px;height:10px;border-radius:2px;background:#fee2e2;"></div> 🔴 Tidak Hadir/Kosong</div>
            </div>
        </div>
    </div>
    @endif

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
                        - {{ $izin->tanggal_selesai->format('d M Y') }}
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

@if(auth()->user()->isAdmin() || $isBranchManager)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ── 1. Map 1: Point Map ───────────────────────────────────────
    @if(auth()->user()->isAdmin())
    try {
        const mapPoint = L.map('map-sebaran-titik').setView([-2.5489, 118.0149], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(mapPoint);

        const kopdesData = @json($kopdesList);
        
        kopdesData.forEach(kop => {
            const lat = parseFloat(kop.latitude);
            const lng = parseFloat(kop.longitude);
            
            let employeeList = '<ul style="margin:4px 0 0 16px;padding:0;font-size:11px;color:#333;">';
            if (kop.users && kop.users.length > 0) {
                kop.users.forEach(u => {
                    employeeList += `<li>${u.name} (<span style="color:#cc0000;font-weight:600;">${u.jabatan}</span>)</li>`;
                });
            } else {
                employeeList += '<li>Tidak ada karyawan</li>';
            }
            employeeList += '</ul>';

            const popupContent = `
                <div style="font-size:12px;width:220px;font-family:inherit;">
                    <strong style="color:#cc0000;font-size:13px;display:block;margin-bottom:2px;">${kop.nama}</strong>
                    <span style="color:#666;font-size:11px;line-height:1.3;display:block;">${kop.alamat}</span>
                    <hr style="margin:6px 0;border:none;border-top:1px solid #ddd;">
                    <strong>Karyawan (${kop.users.length}):</strong>
                    ${employeeList}
                    <div style="margin-top:8px;text-align:right;">
                        <a href="/kopdes/${kop.id}" style="display:inline-block;font-size:11px;font-weight:700;color:#cc0000;text-decoration:none;background:#fff5f5;padding:3px 8px;border-radius:4px;border:1px solid #ffe3e3;">Inspect Detail &rarr;</a>
                    </div>
                </div>
            `;

            L.marker([lat, lng]).addTo(mapPoint)
                .bindPopup(popupContent);
        });

        if (kopdesData.length > 0) {
            const markers = kopdesData.map(k => L.marker([parseFloat(k.latitude), parseFloat(k.longitude)]));
            const group = new L.featureGroup(markers);
            mapPoint.fitBounds(group.getBounds().pad(0.15));
        }

    } catch (e) {
        console.error('Error loading Point Map:', e);
    }

    // ── 2. Map 2: Regional Zonal Map ──────────────────────────────
    try {
        const mapReg = L.map('map-sebaran-regional').setView([-2.5489, 118.0149], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(mapReg);

        const provinceCenters = {
            'Aceh':                  [4.6951,  96.7494],
            'Sumatera Utara':        [2.1154,  99.5450],
            'Sumatera Barat':        [-0.7399, 100.8000],
            'Riau':                  [0.2933,  101.7068],
            'Kepulauan Riau':        [3.9457,  108.1429],
            'Jambi':                 [-1.6101, 103.6131],
            'Sumatera Selatan':      [-3.3194, 103.9144],
            'Bangka Belitung':       [-2.7411, 106.4406],
            'Bengkulu':              [-3.7928, 102.2601],
            'Lampung':               [-4.5585, 105.4068],
            'DKI Jakarta':           [-6.2088, 106.8456],
            'Banten':                [-6.4058, 106.0640],
            'Jawa Barat':            [-6.9147, 107.6098],
            'Jawa Tengah':           [-7.0051, 110.4381],
            'DI Yogyakarta':         [-7.7956, 110.3695],
            'Jawa Timur':            [-7.5360, 112.2384],
            'Bali':                  [-8.4095, 115.1889],
            'Nusa Tenggara Barat':   [-8.6529, 117.3616],
            'Nusa Tenggara Timur':   [-8.6574, 121.0794],
            'Kalimantan Barat':      [0.4766,  110.6889],
            'Kalimantan Tengah':     [-1.6815, 113.3824],
            'Kalimantan Selatan':    [-3.0926, 115.2838],
            'Kalimantan Timur':      [1.6407,  116.4194],
            'Kalimantan Utara':      [3.0731,  116.0413],
            'Sulawesi Utara':        [0.6246,  123.9750],
            'Sulawesi Tengah':       [-1.4300, 121.4456],
            'Sulawesi Selatan':      [-3.6687, 119.9740],
            'Sulawesi Tenggara':     [-4.1448, 122.1746],
            'Gorontalo':             [0.6999,  122.4467],
            'Sulawesi Barat':        [-2.8442, 119.2321],
            'Maluku':                [-3.2385, 130.1453],
            'Maluku Utara':          [1.5709,  127.8088],
            'Papua':                 [-4.2699, 138.0804],
            'Papua Selatan':         [-7.0100, 138.5100],
            'Papua Tengah':          [-3.9913, 136.3801],
            'Papua Pegunungan':      [-4.0000, 139.4000],
        };

        const regData = @json($provinsiList);
        const regMarkers = [];
        
        regData.forEach(reg => {
            const provName = reg.provinsi;
            const count = parseInt(reg.count);
            const center = provinceCenters[provName] || null;
            
            if (center) {
                // Draw circle indicating density
                const circle = L.circle(center, {
                    color: '#cc0000',
                    fillColor: '#cc0000',
                    fillOpacity: 0.35,
                    weight: 2,
                    radius: 40000 + (count * 25000)
                }).addTo(mapReg);

                circle.bindPopup(`
                    <div style="text-align:center;font-size:12px;font-family:inherit;">
                        <strong style="color:#555;">Provinsi ${provName}</strong><br>
                        <span style="font-size:16px;color:#cc0000;font-weight:800;display:block;margin-top:4px;">${count} Koperasi</span>
                    </div>
                `);

                regMarkers.push(L.marker(center));
            }
        });

        if (regMarkers.length > 0) {
            const group = new L.featureGroup(regMarkers);
            mapReg.fitBounds(group.getBounds().pad(0.2));
        }

    } catch (e) {
        console.error('Error loading Regional Map:', e);
    }

    // ── 3. Chart 1: Karyawan per Provinsi ─────────────────────────
    try {
        const provData = @json($chartKaryawanProvinsi);
        const labels = provData.map(d => d.provinsi);
        const values = provData.map(d => d.count);

        new Chart(document.getElementById('chart-provinsi'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Karyawan',
                    data: values,
                    backgroundColor: 'rgba(204, 0, 0, 0.7)',
                    borderColor: 'rgba(204, 0, 0, 1)',
                    borderWidth: 1.5,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1600,
                    easing: 'easeOutBack'
                },
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0, color: '#666' }, grid: { color: '#f3f4f6' } },
                    x: { ticks: { color: '#666' }, grid: { display: false } }
                }
            }
        });
    } catch (e) { console.error(e); }

    // ── 4. Chart 2: Karyawan per Kopdes ───────────────────────────
    try {
        const kopData = @json($chartKaryawanKopdes);
        const labels = kopData.map(d => d.nama);
        const values = kopData.map(d => d.count);

        new Chart(document.getElementById('chart-kopdes'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        '#cc0000', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6',
                        '#ec4899', '#14b8a6', '#f97316', '#6b7280', '#06b6d4'
                     ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1800,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 10, padding: 8, font: { size: 10 } } }
                }
            }
        });
    } catch (e) { console.error(e); }
    @endif

    // ── 5. Chart 3: Top 5 Karyawan Paling Aktif ─────────────────────
    try {
        const aktifData = @json($chartKaryawanAktif);
        const labels = aktifData.map(d => d.name);
        const values = aktifData.map(d => d.total_tepat_waktu);

        new Chart(document.getElementById('chart-aktif'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: 'rgba(16, 185, 129, 0.75)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1.5,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1600,
                    easing: 'easeOutBack'
                },
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0, color: '#666' }, grid: { color: '#f3f4f6' } },
                    y: { ticks: { color: '#666' }, grid: { display: false } }
                }
            }
        });
    } catch (e) { console.error(e); }

    // ── 6. Chart 4: Status Kehadiran Global ───────────────────────
    try {
        const globalData = @json($chartKehadiranGlobal);
        
        const statusMap = {
            'hadir': 'Tepat Waktu',
            'terlambat': 'Terlambat Ringan',
            'sangat_terlambat': 'Sangat Terlambat',
            'alpa': 'Alpa',
            'izin': 'Izin/Sakit'
        };
        const colorMap = {
            'hadir': '#10b981',
            'terlambat': '#f59e0b',
            'sangat_terlambat': '#ef4444',
            'alpa': '#9ca3af',
            'izin': '#3b82f6'
        };

        const labels = globalData.map(d => statusMap[d.status_kehadiran] || d.status_kehadiran);
        const values = globalData.map(d => d.count);
        const colors = globalData.map(d => colorMap[d.status_kehadiran] || '#cbd5e1');

        new Chart(document.getElementById('chart-kehadiran'), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1800,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 10, padding: 8, font: { size: 10 } } }
                }
            }
        });
    } catch (e) { console.error(e); }
});
</script>
@endpush
@endif

@endsection
