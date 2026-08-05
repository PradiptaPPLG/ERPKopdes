@extends('layouts.app')
@section('title', 'Daftarkan Kopdes')
@section('page-title', 'Daftarkan Koperasi Desa Baru')
@section('breadcrumb', 'Manajemen › Data Kopdes › Tambah')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div style="max-width:1000px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">
    
    <div class="card">
        <div class="card-header">
            <span class="card-title">Formulir Registrasi Koperasi Baru</span>
            <a href="{{ route('kopdes.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                
                {{-- LEFT COLUMN: Form Inputs --}}
                <form method="POST" action="{{ route('kopdes.store') }}" style="display:flex;flex-direction:column;gap:14px;margin:0;">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Nama Kopdes <span class="required">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" placeholder="Contoh: Kopdes Cijeungjing" required>
                        @error('nama') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Jl. Raya No..., Desa..., Kecamatan..." required>{{ old('alamat') }}</textarea>
                        @error('alamat') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Desa / Kelurahan</label>
                            <input type="text" name="desa" id="desa" value="{{ old('desa') }}" class="form-control" placeholder="Autofill dari map">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" id="kecamatan" value="{{ old('kecamatan') }}" class="form-control" placeholder="Autofill dari map">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Kabupaten / Kota</label>
                            <input type="text" name="kabupaten" id="kabupaten" value="{{ old('kabupaten') }}" class="form-control" placeholder="Autofill dari map">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi') }}" class="form-control" placeholder="Autofill dari map">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;padding-top:10px;border-top:1px solid #eee;">
                        <div class="form-group">
                            <label class="form-label">Latitude <span class="required">*</span></label>
                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude', '-6.20000000') }}" class="form-control" required readonly style="background:#f3f4f6;font-family:monospace;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitude <span class="required">*</span></label>
                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude', '106.81666600') }}" class="form-control" required readonly style="background:#f3f4f6;font-family:monospace;">
                        </div>
                    </div>

                    <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:10px;">
                        <a href="{{ route('kopdes.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Kopdes</button>
                    </div>
                </form>
                
                {{-- RIGHT COLUMN: Map Search & Map View --}}
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div>
                        <label class="form-label" style="margin-bottom:6px;">Cari Lokasi / Wilayah</label>
                        <div style="display:flex;gap:8px;">
                            <input type="text" id="map-search-input" class="form-control" placeholder="Ketik daerah, misal: Cijeungjing Ciamis" onkeypress="handleSearchKeyPress(event)">
                            <button type="button" class="btn btn-secondary" onclick="searchLocation()" id="search-btn">Cari</button>
                        </div>
                        <div id="search-feedback" style="font-size:11px;color:#cc0000;margin-top:4px;display:none;">
                            Lokasi tidak ditemukan, coba kata kunci lain.
                        </div>
                    </div>

                    <div style="flex:1;position:relative;min-height:350px;">
                        <div id="map-picker" style="position:absolute;inset:0;border-radius:8px;border:1px solid #ddd;z-index:1;"></div>
                    </div>
                    
                    <div style="font-size:11px;color:#6b7280;line-height:1.4;background:#f9fafb;padding:8px 12px;border-radius:6px;border:1px solid #e5e5e5;">
                        💡 <strong>Petunjuk:</strong> Ketik lokasi pada search bar lalu klik "Cari" untuk memposisikan peta, atau geser (drag) pin merah secara manual ke koordinat kantor Koperasi yang presisi. Detail wilayah administratif akan terisi otomatis.
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
let map, marker;
const defaultLat = -6.20000000;
const defaultLng = 106.81666600;

document.addEventListener('DOMContentLoaded', () => {
    try {
        // Inisialisasi peta di koordinat default (Jakarta)
        map = L.map('map-picker').setView([defaultLat, defaultLng], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        
        // Buat marker yang bisa di-drag
        marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);
        
        // Daftarkan event dragend
        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            reverseGeocode(position.lat, position.lng);
        });

        // Coba deteksi geolocation browser di awal jika diizinkan
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => {
                    const myLat = pos.coords.latitude;
                    const myLng = pos.coords.longitude;
                    map.setView([myLat, myLng], 14);
                    marker.setLatLng([myLat, myLng]);
                    reverseGeocode(myLat, myLng);
                },
                err => {
                    // Default to Jakarta
                    reverseGeocode(defaultLat, defaultLng);
                }
            );
        } else {
            reverseGeocode(defaultLat, defaultLng);
        }
        
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
        // Query dengan filter negara Indonesia saja (countrycodes=id)
        const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=1&addressdetails=1&countrycodes=id`;
        const response = await fetch(url, {
            headers: {
                'Accept-Language': 'id'
            }
        });
        const results = await response.json();
        
        if (results && results.length > 0) {
            const loc = results[0];
            const lat = parseFloat(loc.lat);
            const lng = parseFloat(loc.lon);
            
            // Update Map & Marker
            map.setView([lat, lng], 15);
            marker.setLatLng([lat, lng]);
            
            // Update form fields dengan detail geocoding
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

// Fitur Reverse Geocoding: deteksi detail alamat dari koordinat pin
async function reverseGeocode(lat, lng) {
    try {
        const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&addressdetails=1`;
        const response = await fetch(url, {
            headers: {
                'Accept-Language': 'id'
            }
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

// Utility untuk mengisi form input secara aman
function updateFormFields(lat, lng, address) {
    document.getElementById('latitude').value = parseFloat(lat).toFixed(8);
    document.getElementById('longitude').value = parseFloat(lng).toFixed(8);
    
    if (address) {
        // 1. Desa/Kelurahan
        const desa = address.village || address.suburb || address.town || address.hamlet || address.neighbourhood || '';
        document.getElementById('desa').value = desa;
        
        // 2. Kecamatan
        const kecamatan = address.subdistrict || address.city_district || address.municipality || '';
        document.getElementById('kecamatan').value = kecamatan;
        
        // 3. Kabupaten / Kota
        let kabupaten = address.city || address.regency || address.county || '';
        // Bersihkan embel-embel "Kabupaten" jika terduplikasi
        document.getElementById('kabupaten').value = kabupaten;
        
        // 4. Provinsi
        const provinsi = address.state || '';
        document.getElementById('provinsi').value = provinsi;
        
        // Auto-suggest alamat lengkap jika kosong
        const road = address.road || '';
        const house_number = address.house_number ? ' No. ' + address.house_number : '';
        
        let generatedAddress = '';
        if (road) generatedAddress += road + house_number;
        if (desa) generatedAddress += (generatedAddress ? ', Desa ' : 'Desa ') + desa;
        if (kecamatan) generatedAddress += (generatedAddress ? ', Kec. ' : 'Kec. ') + kecamatan;
        if (kabupaten) generatedAddress += (generatedAddress ? ', ' : '') + kabupaten;
        if (provinsi) generatedAddress += (generatedAddress ? ', ' : '') + provinsi;
        
        const currentAlamat = document.getElementById('alamat').value.trim();
        if (!currentAlamat) {
            document.getElementById('alamat').value = generatedAddress;
        }
    }
}
</script>
@endpush
@endsection
