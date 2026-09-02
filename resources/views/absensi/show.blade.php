@extends('layouts.app')
@section('title', 'Detail Absensi')
@section('page-title', 'Detail Data Absensi')
@section('breadcrumb', 'Kehadiran › Detail Absensi')

@section('content')
@if(auth()->user()->canApprove())
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/leaflet-helper.js') }}"></script>
@endif

<div style="max-width:900px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">


    {{-- Main Summary Card --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Absensi: {{ $absensi->user->name }} ({{ $absensi->tanggal->format('d F Y') }})</span>
            <a href="{{ route('absensi.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div>
                    <h4 style="font-size:13px;font-weight:700;color:#1a1a1a;margin-bottom:10px;">Informasi Kehadiran</h4>
                    <div style="display:flex;flex-direction:column;gap:8px;font-size:13px;">
                        <div><span style="color:#888;">Karyawan:</span> <strong>{{ $absensi->user->name }}</strong> ({{ $absensi->user->jabatanLabel() }})</div>
                        <div><span style="color:#888;">Tanggal:</span> <strong>{{ $absensi->tanggal->translatedFormat('l, d F Y') }}</strong></div>
                        <div><span style="color:#888;">Status Kehadiran:</span> <span class="badge badge-{{ $absensi->statusColor() }}">{{ $absensi->statusLabel() }}</span></div>
                        <div><span style="color:#888;">Shift Kerja:</span> <strong>{{ $absensi->jadwal?->shift?->nama_shift ?? 'Default' }}</strong></div>
                        <div><span style="color:#888;">Keterlambatan:</span> <strong>{{ $absensi->keterlambatan_menit > 0 ? $absensi->keterlambatan_menit . ' menit' : 'Tepat Waktu' }}</strong></div>
                        <div><span style="color:#888;">Total Durasi Kerja:</span> <strong>{{ $absensi->durasiKerja() }}</strong></div>
                    </div>
                </div>

                <div>
                    <h4 style="font-size:13px;font-weight:700;color:#1a1a1a;margin-bottom:10px;">Waktu & Waktu Tercatat</h4>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div style="background:#fafafa;padding:12px;border-radius:6px;border:1px solid #e5e5e5;text-align:center;">
                            <div style="font-size:11px;color:#888;">JAM MASUK</div>
                            <div style="font-size:20px;font-weight:700;color:#16a34a;margin-top:2px;">{{ $absensi->jam_masuk ? substr($absensi->jam_masuk,0,5) : '-' }}</div>
                            @if(auth()->user()->canApprove())
                            <div style="font-size:11px;color:#666;margin-top:4px;">{{ $absensi->lokasi_masuk }}</div>
                            @endif
                        </div>
                        <div style="background:#fafafa;padding:12px;border-radius:6px;border:1px solid #e5e5e5;text-align:center;">
                            <div style="font-size:11px;color:#888;">JAM PULANG</div>
                            <div style="font-size:20px;font-weight:700;color:#16a34a;margin-top:2px;">{{ $absensi->jam_pulang ? substr($absensi->jam_pulang,0,5) : '-' }}</div>
                            @if(auth()->user()->canApprove())
                            <div style="font-size:11px;color:#666;margin-top:4px;">{{ $absensi->lokasi_pulang }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Digital Signature & Verification --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Tanda Tangan Digital & Verifikasi</span>
            @if($absensi->tandaTangan)
            <span class="badge {{ $absensi->tandaTangan->status_verifikasi == 'terverifikasi' ? 'badge-success' : ($absensi->tandaTangan->status_verifikasi == 'ditolak' ? 'badge-danger' : 'badge-warning') }}">
                Verifikasi: {{ ucfirst($absensi->tandaTangan->status_verifikasi) }}
            </span>
            @endif
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                {{-- TTD Masuk --}}
                <div style="text-align:center;">
                    <div style="font-size:12px;font-weight:700;color:#555;margin-bottom:8px;">Tanda Tangan Masuk</div>
                    <div style="border:1px dashed #d1d5db;border-radius:6px;padding:10px;background:#fff;min-height:120px;display:flex;align-items:center;justify-content:center;">
                        @if($absensi->tandaTangan?->ttd_masuk)
                        <img src="{{ $absensi->tandaTangan->ttd_masuk }}" style="max-height:100px;">
                        @else
                        <span style="color:#aaa;font-size:12px;">Belum ada TTD</span>
                        @endif
                    </div>
                </div>

                {{-- TTD Pulang --}}
                <div style="text-align:center;">
                    <div style="font-size:12px;font-weight:700;color:#555;margin-bottom:8px;">Tanda Tangan Pulang</div>
                    <div style="border:1px dashed #d1d5db;border-radius:6px;padding:10px;background:#fff;min-height:120px;display:flex;align-items:center;justify-content:center;">
                        @if($absensi->tandaTangan?->ttd_pulang)
                        <img src="{{ $absensi->tandaTangan->ttd_pulang }}" style="max-height:100px;">
                        @else
                        <span style="color:#aaa;font-size:12px;">Belum ada TTD</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Verification Action for Admin --}}
            @if(auth()->user()->canApprove())
            <div style="margin-top:20px;padding-top:16px;border-top:1px solid #eee;">
                <h4 style="font-size:13px;font-weight:700;color:#1a1a1a;margin-bottom:10px;">Verifikasi Administrator</h4>
                <form method="POST" action="{{ route('absensi.verifikasi', $absensi) }}">
                    @csrf
                    <div style="display:grid;grid-template-columns:200px 1fr 140px;gap:12px;align-items:start;">
                        <select name="status_verifikasi" class="form-control" required>
                            <option value="terverifikasi" {{ $absensi->tandaTangan?->status_verifikasi == 'terverifikasi' ? 'selected' : '' }}>Verifikasi (Setujui)</option>
                            <option value="ditolak" {{ $absensi->tandaTangan?->status_verifikasi == 'ditolak' ? 'selected' : '' }}>Tolak TTD</option>
                        </select>
                        <input type="text" name="catatan_verifikasi" value="{{ $absensi->tandaTangan?->catatan_verifikasi }}" class="form-control" placeholder="Catatan verifikator (opsional)">
                        <button type="submit" class="btn btn-primary">Proses Verifikasi</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    {{-- Maps Location Verification for Admin --}}
    @if(auth()->user()->canApprove() && ($absensi->latitude_masuk || $absensi->latitude_pulang))
    <div class="card">
        <div class="card-header">
            <span class="card-title">Verifikasi Lokasi GPS (Peta)</span>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                {{-- Map Masuk --}}
                <div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <h5 style="font-size:12px;font-weight:700;color:#555;margin:0;">Peta Lokasi Masuk</h5>
                        @if($absensi->latitude_masuk && $absensi->longitude_masuk)
                        <a href="https://www.google.com/maps?q={{ $absensi->latitude_masuk }},{{ $absensi->longitude_masuk }}" target="_blank" rel="noopener" class="btn btn-secondary btn-xs">🗺️ Google Maps</a>
                        @endif
                    </div>
                    @if($absensi->latitude_masuk && $absensi->longitude_masuk)
                    <div class="map-quick-toolbar">
                        <span style="color:#64748b;">Layer:</span>
                        <button type="button" class="map-quick-btn active" onclick="switchDetailMapLayer('masuk', '🗺️ Peta Jalan', this)">🗺️ Jalan</button>
                        <button type="button" class="map-quick-btn" onclick="switchDetailMapLayer('masuk', '🛰️ Satelit HD', this)">🛰️ Satelit</button>
                        <button type="button" class="map-quick-btn" onclick="switchDetailMapLayer('masuk', '🏔️ Medan / Topo', this)">🏔️ Medan</button>
                    </div>
                    <div id="map-detail-masuk" style="height:250px;border-radius:6px;border:1px solid #ddd;z-index:1;"></div>
                    <div style="font-size:11px;color:#666;margin-top:6px;">
                        <strong>Lokasi:</strong> {{ $absensi->lokasi_masuk }}<br>
                        <strong>Koordinat:</strong> {{ $absensi->latitude_masuk }}, {{ $absensi->longitude_masuk }}
                    </div>
                    @else
                    <div style="height:250px;border-radius:6px;border:1px dashed #ddd;background:#f9fafb;display:flex;align-items:center;justify-content:center;color:#aaa;font-size:12px;">
                        Tidak ada data lokasi masuk
                    </div>
                    @endif
                </div>

                {{-- Map Pulang --}}
                <div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <h5 style="font-size:12px;font-weight:700;color:#555;margin:0;">Peta Lokasi Pulang</h5>
                        @if($absensi->latitude_pulang && $absensi->longitude_pulang)
                        <a href="https://www.google.com/maps?q={{ $absensi->latitude_pulang }},{{ $absensi->longitude_pulang }}" target="_blank" rel="noopener" class="btn btn-secondary btn-xs">🗺️ Google Maps</a>
                        @endif
                    </div>
                    @if($absensi->latitude_pulang && $absensi->longitude_pulang)
                    <div class="map-quick-toolbar">
                        <span style="color:#64748b;">Layer:</span>
                        <button type="button" class="map-quick-btn active" onclick="switchDetailMapLayer('pulang', '🗺️ Peta Jalan', this)">🗺️ Jalan</button>
                        <button type="button" class="map-quick-btn" onclick="switchDetailMapLayer('pulang', '🛰️ Satelit HD', this)">🛰️ Satelit</button>
                        <button type="button" class="map-quick-btn" onclick="switchDetailMapLayer('pulang', '🏔️ Medan / Topo', this)">🏔️ Medan</button>
                    </div>
                    <div id="map-detail-pulang" style="height:250px;border-radius:6px;border:1px solid #ddd;z-index:1;"></div>
                    <div style="font-size:11px;color:#666;margin-top:6px;">
                        <strong>Lokasi:</strong> {{ $absensi->lokasi_pulang }}<br>
                        <strong>Koordinat:</strong> {{ $absensi->latitude_pulang }}, {{ $absensi->longitude_pulang }}
                    </div>
                    @else
                    <div style="height:250px;border-radius:6px;border:1px dashed #ddd;background:#f9fafb;display:flex;align-items:center;justify-content:center;color:#aaa;font-size:12px;">
                        Tidak ada data lokasi pulang
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

@if(auth()->user()->canApprove())
@push('scripts')
<script>
let mapMasukObj, mapPulangObj;

function switchDetailMapLayer(type, layerName, btn) {
    const obj = type === 'masuk' ? mapMasukObj : mapPulangObj;
    if (!obj || !obj.map || !obj.baseMaps) return;
    const parent = btn.parentElement;
    parent.querySelectorAll('.map-quick-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const targetLayer = obj.baseMaps[layerName];
    if (targetLayer) {
        Object.values(obj.baseMaps).forEach(l => {
            if (obj.map.hasLayer(l)) obj.map.removeLayer(l);
        });
        obj.map.addLayer(targetLayer);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    @if($absensi->latitude_masuk && $absensi->longitude_masuk)
        try {
            const latMasuk = {{ $absensi->latitude_masuk }};
            const lngMasuk = {{ $absensi->longitude_masuk }};
            mapMasukObj = ErpKopdesMap.initMap('map-detail-masuk', [latMasuk, lngMasuk], 16, "🗺️ Peta Jalan");
            const mapMasuk = mapMasukObj.map;

            const pinIcon = ErpKopdesMap.createKopdesIcon('#16a34a', '📍');

            L.marker([latMasuk, lngMasuk], { icon: pinIcon }).addTo(mapMasuk)
                .bindPopup("<strong>Lokasi Absen Masuk:</strong><br>{{ $absensi->user->name }}").openPopup();
        } catch (e) {
            console.error('Error loading check-in map:', e);
        }
    @endif

    @if($absensi->latitude_pulang && $absensi->longitude_pulang)
        try {
            const latPulang = {{ $absensi->latitude_pulang }};
            const lngPulang = {{ $absensi->longitude_pulang }};
            mapPulangObj = ErpKopdesMap.initMap('map-detail-pulang', [latPulang, lngPulang], 16, "🗺️ Peta Jalan");
            const mapPulang = mapPulangObj.map;

            const pinIcon = ErpKopdesMap.createKopdesIcon('#16a34a', '🏠');

            L.marker([latPulang, lngPulang], { icon: pinIcon }).addTo(mapPulang)
                .bindPopup("<strong>Lokasi Absen Pulang:</strong><br>{{ $absensi->user->name }}").openPopup();
        } catch (e) {
            console.error('Error loading check-out map:', e);
        }
    @endif
});
</script>
@endpush
@endif
@endsection

