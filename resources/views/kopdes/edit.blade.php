@extends('layouts.app')
@section('title', 'Edit Kopdes')
@section('page-title', 'Edit Data Koperasi Desa')
@section('breadcrumb', 'Manajemen › Data Kopdes › Edit')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/leaflet-helper.js') }}"></script>

<div style="max-width:1000px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">
    
    <div class="card">
        <div class="card-header">
            <span class="card-title">Edit Formulir Koperasi: {{ $kopde->nama }}</span>
            <a href="{{ route('kopdes.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                
                {{-- LEFT COLUMN: Form Inputs --}}
                <form method="POST" action="{{ route('kopdes.update', $kopde) }}" style="display:flex;flex-direction:column;gap:14px;margin:0;">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label">Nama Kopdes <span class="required">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $kopde->nama) }}" class="form-control" required>
                        @error('nama') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{ old('alamat', $kopde->alamat) }}</textarea>
                        @error('alamat') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Desa / Kelurahan</label>
                            <input type="text" name="desa" id="desa" value="{{ old('desa', $kopde->desa) }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" id="kecamatan" value="{{ old('kecamatan', $kopde->kecamatan) }}" class="form-control">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Kabupaten / Kota</label>
                            <input type="text" name="kabupaten" id="kabupaten" value="{{ old('kabupaten', $kopde->kabupaten) }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', $kopde->provinsi) }}" class="form-control">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;padding-top:10px;border-top:1px solid #eee;">
                        <div class="form-group">
                            <label class="form-label">Latitude <span class="required">*</span></label>
                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $kopde->latitude) }}" class="form-control" required readonly style="background:#f3f4f6;font-family:monospace;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitude <span class="required">*</span></label>
                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $kopde->longitude) }}" class="form-control" required readonly style="background:#f3f4f6;font-family:monospace;">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1.5fr;gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Radius Absen (Meter) <span class="required">*</span></label>
                            <input type="number" name="radius_meter" id="radius_meter" value="{{ old('radius_meter', $kopde->radius_meter) }}" min="5" class="form-control" placeholder="Contoh: 50" required oninput="updateGeofenceCircle()">
                            @error('radius_meter') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Manager Cabang</label>
                            <select name="manager_id" id="manager_id" class="form-control" style="appearance:auto;">
                                <option value="">-- Pilih Manager --</option>
                                @foreach($managers as $m)
                                    <option value="{{ $m->id }}" {{ old('manager_id', $kopde->manager_id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }} ({{ $m->jabatanLabel() }})
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:10px;">
                        <a href="{{ route('kopdes.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
                
                {{-- RIGHT COLUMN: Map Search & Map View --}}
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div>
                        <label class="form-label" style="margin-bottom:6px;">Cari Lokasi / Wilayah Baru</label>
                        <div style="display:flex;gap:8px;">
                            <input type="text" id="map-search-input" class="form-control" placeholder="Ketik daerah baru..." onkeypress="handleSearchKeyPress(event)">
                            <button type="button" class="btn btn-secondary" onclick="searchLocation()" id="search-btn">Cari</button>
                        </div>
                        <div id="search-feedback" style="font-size:11px;color:#cc0000;margin-top:4px;display:none;">
                            Lokasi tidak ditemukan, coba kata kunci lain.
                        </div>
                    </div>

                    <div class="map-quick-toolbar">
                        <span style="color:#64748b;">Layer:</span>
                        <button type="button" class="map-quick-btn active" onclick="switchPickerMapLayer('🗺️ Peta Jalan', this)">🗺️ Jalan</button>
                        <button type="button" class="map-quick-btn" onclick="switchPickerMapLayer('🛰️ Satelit HD', this)">🛰️ Satelit HD</button>
                        <button type="button" class="map-quick-btn" onclick="switchPickerMapLayer('🏔️ Medan / Topo', this)">🏔️ Medan</button>
                        <button type="button" class="map-quick-btn" onclick="switchPickerMapLayer('🌙 Mode Gelap', this)">🌙 Mode Gelap</button>
                    </div>

                    <div style="flex:1;position:relative;min-height:350px;">
                        <div id="map-picker" style="position:absolute;inset:0;border-radius:8px;border:1px solid #ddd;z-index:1;"></div>
                    </div>
                    
                    <div style="font-size:11px;color:#6b7280;line-height:1.4;background:#f9fafb;padding:8px 12px;border-radius:6px;border:1px solid #e5e5e5;">
                        💡 <strong>Petunjuk:</strong> Anda dapat menggeser (drag) marker merah ke koordinat baru atau menggunakan kolom pencarian untuk memindahkan lokasi Koperasi secara instan. Area geofence akan otomatis ter-update.
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
let pickerMapObj, map, marker, geofenceCircle;
const initialLat = {{ $kopde->latitude }};
const initialLng = {{ $kopde->longitude }};

function switchPickerMapLayer(layerName, btn) {
    if (!pickerMapObj || !pickerMapObj.map || !pickerMapObj.baseMaps) return;
    const parent = btn.parentElement;
    parent.querySelectorAll('.map-quick-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const targetLayer = pickerMapObj.baseMaps[layerName];
    if (targetLayer) {
        Object.values(pickerMapObj.baseMaps).forEach(l => {
            if (pickerMapObj.map.hasLayer(l)) pickerMapObj.map.removeLayer(l);
        });
        pickerMapObj.map.addLayer(targetLayer);
    }
}

function updateGeofenceCircle() {
    if (!geofenceCircle || !marker) return;
    const radiusVal = parseFloat(document.getElementById('radius_meter').value) || 50;
    const currentPos = marker.getLatLng();
    geofenceCircle.setLatLng(currentPos);
    geofenceCircle.setRadius(radiusVal);
}

document.addEventListener('DOMContentLoaded', () => {
    try {
        // Inisialisasi peta dengan ErpKopdesMap
        pickerMapObj = ErpKopdesMap.initMap('map-picker', [initialLat, initialLng], 15, "🗺️ Peta Jalan");
        map = pickerMapObj.map;
        
        const pinIcon = ErpKopdesMap.createKopdesIcon('#cc0000', '📍');

        // Marker awal
        marker = L.marker([initialLat, initialLng], {
            draggable: true,
            icon: pinIcon
        }).addTo(map);
        
        // Geofence circle
        const initialRadius = parseFloat(document.getElementById('radius_meter').value) || 50;
        geofenceCircle = L.circle([initialLat, initialLng], {
            color: '#dc2626',
            fillColor: '#ef4444',
            fillOpacity: 0.2,
            weight: 2,
            radius: initialRadius
        }).addTo(map);

        marker.on('drag', function (e) {
            const pos = marker.getLatLng();
            geofenceCircle.setLatLng(pos);
        });

        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            geofenceCircle.setLatLng(position);
            reverseGeocode(position.lat, position.lng);
        });
        
    } catch (error) {
        console.error('Error loading Leaflet map:', error);
    }
});

function handleSearchKeyPress(e) {
    if (e.key === 'Enter') {
        searchLocation();
    }
}

// Fitur Geocoding: cari lokasi menggunakan Nominatim API
async function searchLocation() {
    const query = document.getElementById('map-search-input').value.trim();
    if (!query) return;
    
    const searchBtn = document.getElementById('search-btn');
    searchBtn.disabled = true;
    searchBtn.innerText = 'Mencari...';
    document.getElementById('search-feedback').style.display = 'none';
    
    try {
        const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=1&addressdetails=1&countrycodes=id`;
        const response = await fetch(url, {
            headers: { 'Accept-Language': 'id' }
        });
        const results = await response.json();
        
        if (results && results.length > 0) {
            const loc = results[0];
            const lat = parseFloat(loc.lat);
            const lng = parseFloat(loc.lon);
            
            // Update Map & Marker & Geofence
            map.setView([lat, lng], 16);
            marker.setLatLng([lat, lng]);
            geofenceCircle.setLatLng([lat, lng]);
            
            // Update form fields
            updateFormFields(lat, lng, loc.address);
        } else {
            document.getElementById('search-feedback').style.display = 'block';
        }
    } catch (err) {
        console.error('Geocoding error:', err);
    } finally {
        searchBtn.disabled = false;
        searchBtn.innerText = 'Cari';
    }
}

// Reverse Geocoding
async function reverseGeocode(lat, lng) {
    try {
        const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&addressdetails=1`;
        const response = await fetch(url, {
            headers: { 'Accept-Language': 'id' }
        });
        const result = await response.json();
        
        if (result && result.address) {
            updateFormFields(lat, lng, result.address);
        } else {
            updateFormFields(lat, lng, null);
        }
    } catch (err) {
        console.error('Reverse Geocoding error:', err);
        updateFormFields(lat, lng, null);
    }
}

function updateFormFields(lat, lng, address) {
    document.getElementById('latitude').value = parseFloat(lat).toFixed(8);
    document.getElementById('longitude').value = parseFloat(lng).toFixed(8);
    
    if (address) {
        const desa = address.village || address.suburb || address.town || address.hamlet || address.neighbourhood || '';
        document.getElementById('desa').value = desa;
        
        const kecamatan = address.subdistrict || address.city_district || address.municipality || '';
        document.getElementById('kecamatan').value = kecamatan;
        
        const kabupaten = address.city || address.regency || address.county || '';
        document.getElementById('kabupaten').value = kabupaten;
        
        const provinsi = address.state || '';
        document.getElementById('provinsi').value = provinsi;
        
        const road = address.road || '';
        const house_number = address.house_number ? ' No. ' + address.house_number : '';
        
        let generatedAddress = '';
        if (road) generatedAddress += road + house_number;
        if (desa) generatedAddress += (generatedAddress ? ', Desa ' : 'Desa ') + desa;
        if (kecamatan) generatedAddress += (generatedAddress ? ', Kec. ' : 'Kec. ') + kecamatan;
        if (kabupaten) generatedAddress += (generatedAddress ? ', ' : '') + kabupaten;
        if (provinsi) generatedAddress += (generatedAddress ? ', ' : '') + provinsi;
        
        document.getElementById('alamat').value = generatedAddress;
    }
}
</script>
@endpush
@endsection

