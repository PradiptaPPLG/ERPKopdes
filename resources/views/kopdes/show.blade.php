@extends('layouts.app')
@section('title', 'Inspect Kopdes - ' . $kopde->nama)
@section('page-title', 'Inspect Koperasi Desa')
@section('breadcrumb', 'Manajemen › Data Kopdes › Inspect')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/leaflet-helper.js') }}"></script>

<div style="display:flex;flex-direction:column;gap:20px;max-width:1000px;margin:0 auto;">

    {{-- Top back button --}}
    <div style="display:flex;justify-content:flex-end;">
        <a href="{{ route('kopdes.index') }}" class="btn btn-secondary">
            Kembali ke Daftar
        </a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        
        {{-- Left: Details Card --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Informasi Detail Kopdes</span>
                <a href="{{ route('kopdes.edit', $kopde) }}" class="btn btn-secondary btn-xs">Edit Data</a>
            </div>
            <div class="card-body" style="padding:20px;display:flex;flex-direction:column;gap:14px;font-size:13px;">
                <div>
                    <span style="color:#6b7280;display:block;margin-bottom:2px;">Nama Koperasi:</span>
                    <strong style="font-size:16px;color:#cc0000;">{{ $kopde->nama }}</strong>
                </div>
                <div>
                    <span style="color:#6b7280;display:block;margin-bottom:2px;">Manager Cabang:</span>
                    <strong style="font-size:14px;color:#1e293b;">{{ $kopde->manager ? $kopde->manager->name : 'Belum Ditunjuk' }}</strong>
                </div>
                <div>
                    <span style="color:#6b7280;display:block;margin-bottom:2px;">Alamat Lengkap:</span>
                    <strong style="color:#111827;line-height:1.4;">{{ $kopde->alamat }}</strong>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <span style="color:#6b7280;display:block;margin-bottom:2px;">Desa/Kelurahan:</span>
                        <strong style="color:#111827;">{{ $kopde->desa ?? '-' }}</strong>
                    </div>
                    <div>
                        <span style="color:#6b7280;display:block;margin-bottom:2px;">Kecamatan:</span>
                        <strong style="color:#111827;">{{ $kopde->kecamatan ?? '-' }}</strong>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <span style="color:#6b7280;display:block;margin-bottom:2px;">Kabupaten/Kota:</span>
                        <strong style="color:#111827;">{{ $kopde->kabupaten ?? '-' }}</strong>
                    </div>
                    <div>
                        <span style="color:#6b7280;display:block;margin-bottom:2px;">Provinsi:</span>
                        <strong style="color:#111827;">{{ $kopde->provinsi ?? '-' }}</strong>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;padding-top:10px;border-top:1px solid #eee;">
                    <div>
                        <span style="color:#6b7280;display:block;margin-bottom:2px;">Latitude:</span>
                        <code style="font-weight:700;">{{ $kopde->latitude }}</code>
                    </div>
                    <div>
                        <span style="color:#6b7280;display:block;margin-bottom:2px;">Longitude:</span>
                        <code style="font-weight:700;">{{ $kopde->longitude }}</code>
                    </div>
                    <div>
                        <span style="color:#6b7280;display:block;margin-bottom:2px;">Radius Geofence:</span>
                        <strong style="color:#0284c7;font-weight:700;">{{ $kopde->radius_meter }} Meter</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Map Location Card --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Titik Koordinat GPS</span>
                <a href="https://www.google.com/maps?q={{ $kopde->latitude }},{{ $kopde->longitude }}" target="_blank" rel="noopener" class="btn btn-secondary btn-xs">
                    🗺️ Google Maps
                </a>
            </div>
            <div class="card-body" style="padding:16px;">
                <div class="map-quick-toolbar">
                    <span style="color:#64748b;">Layer:</span>
                    <button type="button" class="map-quick-btn active" onclick="switchKopdesMapLayer('🗺️ Peta Jalan', this)">🗺️ Jalan</button>
                    <button type="button" class="map-quick-btn" onclick="switchKopdesMapLayer('🛰️ Satelit HD', this)">🛰️ Satelit HD</button>
                    <button type="button" class="map-quick-btn" onclick="switchKopdesMapLayer('🏔️ Medan / Topo', this)">🏔️ Medan</button>
                    <button type="button" class="map-quick-btn" onclick="switchKopdesMapLayer('🌙 Mode Gelap', this)">🌙 Mode Gelap</button>
                </div>
                <div id="kopdes-map" style="height:270px;border-radius:6px;border:1px solid #ddd;z-index:1;"></div>
            </div>
        </div>

    </div>

    {{-- Bottom: Employees involved in this Kopdes --}}
    <div class="card">
        <div class="card-header" style="background:#fafafa;">
            <span class="card-title" style="color:#cc0000;">Karyawan / Staf Terlibat</span>
            <span class="badge badge-primary" style="font-size:12px;padding:4px 8px;">
                {{ $kopde->users->count() }} Orang
            </span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Karyawan</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Status Kerja</th>
                        <th>No HP / WA</th>
                        <th style="width:100px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kopde->users as $u)
                    <tr>
                        <td>
                            <div style="font-weight:700;color:#111827;">{{ $u->name }}</div>
                            <div style="font-size:11px;color:#6b7280;">{{ $u->email }}</div>
                        </td>
                        <td><code style="font-weight:600;">{{ $u->nip ?? '-' }}</code></td>
                        <td>
                            <span style="font-weight:600;color:#374151;">{{ $u->jabatanLabel() }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $u->status == 'aktif' ? 'success' : ($u->status == 'cuti' ? 'warning' : 'danger') }}">
                                {{ ucfirst($u->status) }}
                            </span>
                        </td>
                        <td>{{ $u->no_hp ?? '-' }}</td>
                        <td style="text-align:center;">
                            <a href="{{ route('karyawan.show', $u) }}" class="btn btn-secondary btn-xs">
                                Profil
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:#888;">
                            Belum ada karyawan yang ditugaskan di Koperasi Desa ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
let kopdesMapObj;

function switchKopdesMapLayer(layerName, btn) {
    if (!kopdesMapObj || !kopdesMapObj.map || !kopdesMapObj.baseMaps) return;
    const parent = btn.parentElement;
    parent.querySelectorAll('.map-quick-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const targetLayer = kopdesMapObj.baseMaps[layerName];
    if (targetLayer) {
        Object.values(kopdesMapObj.baseMaps).forEach(l => {
            if (kopdesMapObj.map.hasLayer(l)) kopdesMapObj.map.removeLayer(l);
        });
        kopdesMapObj.map.addLayer(targetLayer);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    try {
        const lat = {{ $kopde->latitude }};
        const lng = {{ $kopde->longitude }};
        const radius = {{ $kopde->radius_meter }};
        
        // Inisialisasi Peta via ErpKopdesMap
        kopdesMapObj = ErpKopdesMap.initMap('kopdes-map', [lat, lng], 15, "🗺️ Peta Jalan");
        const map = kopdesMapObj.map;
        
        // Custom Pin Icon
        const pinIcon = ErpKopdesMap.createKopdesIcon('#cc0000', '🏢');

        const gmapsUrl = `https://www.google.com/maps?q=${lat},${lng}`;
        const popupContent = `
            <div style="font-size:12px;width:220px;font-family:inherit;">
                <strong style="color:#cc0000;font-size:14px;display:block;margin-bottom:3px;">{{ $kopde->nama }}</strong>
                <span style="color:#666;font-size:11px;line-height:1.3;display:block;">{{ $kopde->alamat }}</span>
                <div style="margin-top:6px;font-size:11px;color:#0284c7;font-weight:600;">
                    Geofence: ${radius} Meter
                </div>
                <div style="margin-top:10px;text-align:right;">
                    <a href="${gmapsUrl}" target="_blank" rel="noopener" style="display:inline-block;font-size:11px;font-weight:700;color:#2563eb;text-decoration:none;background:#eff6ff;padding:3px 8px;border-radius:4px;border:1px solid #bfdbfe;">🗺️ Buka Google Maps</a>
                </div>
            </div>
        `;

        // Marker & Popup
        L.marker([lat, lng], { icon: pinIcon }).addTo(map)
            .bindPopup(popupContent)
            .openPopup();

        // Lingkaran Geofence
        L.circle([lat, lng], {
            color: '#dc2626',
            fillColor: '#ef4444',
            fillOpacity: 0.2,
            weight: 2,
            radius: radius
        }).addTo(map);
            
    } catch (error) {
        console.error('Error loading Leaflet map:', error);
    }
});
</script>
@endpush
@endsection

