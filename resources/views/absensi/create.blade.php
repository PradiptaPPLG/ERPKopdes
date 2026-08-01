@extends('layouts.app')
@section('title', 'Absen Hari Ini')
@section('page-title', 'Absensi Karyawan')
@section('breadcrumb', 'Kehadiran › Absen Hari Ini')

@section('content')
{{-- Hanya load Leaflet untuk admin. Karyawan tidak perlu Leaflet sama sekali. --}}
@if(auth()->user()->canApprove())
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endif
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<div style="max-width:900px;margin:0 auto;">

    {{-- Today Shift Banner --}}
    <div class="card" style="margin-bottom:20px;padding:20px;border-left:4px solid {{ $jadwal?->shift?->kode_warna ?? '#cc0000' }};">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
            <div>
                <span class="badge badge-primary" style="margin-bottom:6px;">
                    Shift Hari Ini: {{ $jadwal?->shift?->nama_shift ?? 'Default' }}
                </span>
                <h3 style="font-size:16px;font-weight:700;color:#1a1a1a;">
                    {{ $today->translatedFormat('l, d F Y') }}
                </h3>
                <div style="font-size:12px;color:#666;margin-top:2px;">
                    Jam Kerja: {{ $jadwal?->shift?->jam_mulai_format ?? '08:00' }} - {{ $jadwal?->shift?->jam_selesai_format ?? '17:00' }} WIB
                </div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:11px;color:#888;">Waktu Sekarang</div>
                <div id="liveClock" style="font-size:24px;font-weight:700;color:#cc0000;font-family:monospace;">--:--:--</div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        {{-- Absen Masuk Card --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">1. Absen Masuk</span>
                @if($absensi?->jam_masuk)
                <span class="badge badge-success">Sudah Masuk</span>
                @endif
            </div>
            <div class="card-body">
                @if($absensi?->jam_masuk)
                <div style="text-align:center;padding:20px 0;">
                    <div style="font-size:12px;color:#888;">Tercatat Pukul</div>
                    <div style="font-size:28px;font-weight:700;color:#16a34a;margin:6px 0;">
                        {{ substr($absensi->jam_masuk,0,5) }} WIB
                    </div>
                    <span class="badge badge-{{ $absensi->statusColor() }}" style="margin-top:10px;">
                        {{ $absensi->statusLabel() }}
                    </span>
                </div>
                @else
                <form method="POST" action="{{ route('absensi.masuk') }}" id="formMasuk">
                    @csrf
                    <input type="hidden" name="latitude"  id="lat_masuk">
                    <input type="hidden" name="longitude" id="lng_masuk">
                    <input type="hidden" name="lokasi"    id="lokasi_masuk">
                    <input type="hidden" name="ttd_masuk" id="ttd_masuk_data">

                    {{-- Status GPS (tanpa peta, tanpa koordinat) --}}
                    <div class="form-group">
                        <div id="gps_status_masuk" style="display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:8px;background:#f9fafb;border:1px solid #e5e5e5;font-size:13px;color:#888;">
                            <svg id="gps_spinner_masuk" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="animation:spin 1.2s linear infinite;flex-shrink:0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 100 10h-2a8 8 0 01-8-8z"/>
                            </svg>
                            <span id="gps_text_masuk">Mendeteksi lokasi GPS, mohon tunggu…</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanda Tangan Digital <span class="required">*</span></label>
                        <div class="signature-wrapper">
                            <canvas id="pad_masuk" height="150"></canvas>
                            <div class="signature-toolbar">
                                <button type="button" class="btn btn-secondary btn-xs" onclick="clearPad('masuk')">Bersihkan</button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btn_masuk" class="btn btn-primary" disabled
                        style="width:100%;justify-content:center;margin-top:10px;opacity:0.6;"
                        onclick="prepareSubmit('masuk', event)">
                        Absen Masuk
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Absen Pulang Card --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">2. Absen Pulang</span>
                @if($absensi?->jam_pulang)
                <span class="badge badge-success">Sudah Pulang</span>
                @endif
            </div>
            <div class="card-body">
                @if($absensi?->jam_pulang)
                <div style="text-align:center;padding:20px 0;">
                    <div style="font-size:12px;color:#888;">Tercatat Pukul</div>
                    <div style="font-size:28px;font-weight:700;color:#16a34a;margin:6px 0;">
                        {{ substr($absensi->jam_pulang,0,5) }} WIB
                    </div>
                    <div style="font-size:12px;color:#888;margin-top:10px;">
                        Durasi Kerja: <strong>{{ $absensi->durasiKerja() }}</strong>
                    </div>
                </div>
                @elseif(!$absensi?->jam_masuk)
                <div style="text-align:center;padding:40px 10px;color:#888;font-size:13px;">
                    Anda harus melakukan Absen Masuk terlebih dahulu.
                </div>
                @else
                <form method="POST" action="{{ route('absensi.pulang') }}" id="formPulang">
                    @csrf
                    <input type="hidden" name="latitude"   id="lat_pulang">
                    <input type="hidden" name="longitude"  id="lng_pulang">
                    <input type="hidden" name="lokasi"     id="lokasi_pulang">
                    <input type="hidden" name="ttd_pulang" id="ttd_pulang_data">

                    {{-- Status GPS (tanpa peta, tanpa koordinat) --}}
                    <div class="form-group">
                        <div id="gps_status_pulang" style="display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:8px;background:#f9fafb;border:1px solid #e5e5e5;font-size:13px;color:#888;">
                            <svg id="gps_spinner_pulang" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="animation:spin 1.2s linear infinite;flex-shrink:0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 100 10h-2a8 8 0 01-8-8z"/>
                            </svg>
                            <span id="gps_text_pulang">Mendeteksi lokasi GPS, mohon tunggu…</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanda Tangan Digital <span class="required">*</span></label>
                        <div class="signature-wrapper">
                            <canvas id="pad_pulang" height="150"></canvas>
                            <div class="signature-toolbar">
                                <button type="button" class="btn btn-secondary btn-xs" onclick="clearPad('pulang')">Bersihkan</button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btn_pulang" class="btn btn-success" disabled
                        style="width:100%;justify-content:center;margin-top:10px;opacity:0.6;"
                        onclick="prepareSubmit('pulang', event)">
                        Absen Pulang
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>

</div>

<style>
@keyframes spin { 0%{transform:rotate(0deg)} 100%{transform:rotate(360deg)} }
</style>

<script>
// Live clock
setInterval(() => {
    const now = new Date();
    document.getElementById('liveClock').innerText = now.toTimeString().split(' ')[0];
}, 1000);

let padMasuk, padPulang;

document.addEventListener('DOMContentLoaded', () => {
    // Setup signature pads
    const canvasMasuk = document.getElementById('pad_masuk');
    if (canvasMasuk) {
        canvasMasuk.width = canvasMasuk.parentElement.clientWidth;
        padMasuk = new SignaturePad(canvasMasuk);
    }
    const canvasPulang = document.getElementById('pad_pulang');
    if (canvasPulang) {
        canvasPulang.width = canvasPulang.parentElement.clientWidth;
        padPulang = new SignaturePad(canvasPulang);
    }

    // Ambil GPS di background : TANPA tampilkan ke user
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            pos => setGps(pos.coords.latitude, pos.coords.longitude),
            err => setGpsFallback(),
            { timeout: 10000, maximumAge: 0 }
        );
    } else {
        setGpsFallback();
    }
});

function setGps(lat, lng) {
    // Isi hidden fields (lat/lng dicatat server, TIDAK ditampilkan ke karyawan)
    ['masuk', 'pulang'].forEach(type => {
        const latEl  = document.getElementById(`lat_${type}`);
        const lngEl  = document.getElementById(`lng_${type}`);
        const locEl  = document.getElementById(`lokasi_${type}`);
        const btn    = document.getElementById(`btn_${type}`);
        const status = document.getElementById(`gps_status_${type}`);
        const text   = document.getElementById(`gps_text_${type}`);
        if (!latEl) return;

        latEl.value = lat;
        lngEl.value = lng;
        locEl.value = 'GPS Terverifikasi';

        // Tampilkan status berhasil : tanpa koordinat
        if (status) {
            status.style.background = '#f0fdf4';
            status.style.borderColor = '#86efac';
            status.style.color = '#15803d';
        }
        if (text) text.innerHTML = '<strong>✓ Lokasi berhasil terdeteksi</strong>';
        const spinner = document.getElementById(`gps_spinner_${type}`);
        if (spinner) {
            spinner.style.animation = 'none';
            spinner.style.stroke = '#16a34a';
        }
        // Aktifkan tombol absen
        if (btn) {
            btn.disabled = false;
            btn.style.opacity = '1';
        }
    });
}

function setGpsFallback() {
    ['masuk', 'pulang'].forEach(type => {
        const latEl  = document.getElementById(`lat_${type}`);
        const btn    = document.getElementById(`btn_${type}`);
        const status = document.getElementById(`gps_status_${type}`);
        const text   = document.getElementById(`gps_text_${type}`);
        if (!latEl) return;

        // Pakai koordinat fallback kantor (disimpan server, tidak ditampilkan)
        document.getElementById(`lat_${type}`).value = '-6.200000';
        document.getElementById(`lng_${type}`).value = '106.816666';
        document.getElementById(`lokasi_${type}`).value = 'Lokasi Manual';

        if (status) {
            status.style.background = '#fffbeb';
            status.style.borderColor = '#fcd34d';
            status.style.color = '#92400e';
        }
        if (text) text.innerHTML = '⚠ GPS tidak tersedia, menggunakan lokasi default';
        const spinner = document.getElementById(`gps_spinner_${type}`);
        if (spinner) {
            spinner.style.animation = 'none';
            spinner.style.stroke = '#d97706';
        }
        // Tetap aktifkan tombol agar karyawan bisa absen
        if (btn) {
            btn.disabled = false;
            btn.style.opacity = '1';
        }
    });
}

function clearPad(type) {
    if (type === 'masuk'  && padMasuk)  padMasuk.clear();
    if (type === 'pulang' && padPulang) padPulang.clear();
}

function prepareSubmit(type, e) {
    const pad = type === 'masuk' ? padMasuk : padPulang;
    if (!pad || pad.isEmpty()) {
        alert('Tanda tangan digital wajib diisi!');
        e.preventDefault();
        return false;
    }
    document.getElementById(`ttd_${type}_data`).value = pad.toDataURL();
}
</script>
@endsection
