@extends('layouts.app')
@section('title', 'Absen Hari Ini')
@section('page-title', 'Absensi Karyawan')
@section('breadcrumb', 'Kehadiran › Absen Hari Ini')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
                    <div style="font-size:12px;color:#555;">{{ $absensi->lokasi_masuk }}</div>
                    <span class="badge badge-{{ $absensi->statusColor() }}" style="margin-top:10px;">
                        {{ $absensi->statusLabel() }}
                    </span>
                </div>
                @else
                <form method="POST" action="{{ route('absensi.masuk') }}" id="formMasuk">
                    @csrf
                    <input type="hidden" name="latitude" id="lat_masuk">
                    <input type="hidden" name="longitude" id="lng_masuk">
                    <input type="hidden" name="lokasi" id="lokasi_masuk">
                    <input type="hidden" name="ttd_masuk" id="ttd_masuk_data">

                    <div class="form-group">
                        <label class="form-label">Lokasi Anda <span class="required">*</span></label>
                        <div id="map-masuk"></div>
                        <div id="loc_info_masuk" style="font-size:11px;color:#666;margin-top:6px;">Mendapatkan lokasi GPS...</div>
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

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:10px;" onclick="prepareSubmit('masuk', event)">
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
                    <div style="font-size:12px;color:#555;">{{ $absensi->lokasi_pulang }}</div>
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
                    <input type="hidden" name="latitude" id="lat_pulang">
                    <input type="hidden" name="longitude" id="lng_pulang">
                    <input type="hidden" name="lokasi" id="lokasi_pulang">
                    <input type="hidden" name="ttd_pulang" id="ttd_pulang_data">

                    <div class="form-group">
                        <label class="form-label">Lokasi Anda <span class="required">*</span></label>
                        <div id="map-pulang"></div>
                        <div id="loc_info_pulang" style="font-size:11px;color:#666;margin-top:6px;">Mendapatkan lokasi GPS...</div>
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

                    <button type="submit" class="btn btn-success" style="width:100%;justify-content:center;margin-top:10px;" onclick="prepareSubmit('pulang', event)">
                        Absen Pulang
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>

</div>

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

    // Geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            initMap('masuk', lat, lng);
            initMap('pulang', lat, lng);
        }, err => {
            // Fallback location: Jakarta Kopdes office
            const fallbackLat = -6.200000, fallbackLng = 106.816666;
            initMap('masuk', fallbackLat, fallbackLng);
            initMap('pulang', fallbackLat, fallbackLng);
        });
    }
});

function initMap(type, lat, lng) {
    const container = document.getElementById(`map-${type}`);
    if (!container) return;

    document.getElementById(`lat_${type}`).value = lat;
    document.getElementById(`lng_${type}`).value = lng;
    document.getElementById(`lokasi_${type}`).value = "Kantor Koperasi Desa Maju Bersama";
    document.getElementById(`loc_info_${type}`).innerText = `Lat: ${lat.toFixed(5)}, Lng: ${lng.toFixed(5)}`;

    const map = L.map(`map-${type}`).setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup("Lokasi Absensi Anda").openPopup();
}

function clearPad(type) {
    if (type === 'masuk' && padMasuk) padMasuk.clear();
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
