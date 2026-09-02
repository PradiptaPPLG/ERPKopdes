/**
 * ERP Kopdes - Leaflet Map Helper Suite
 * Provides unified base tile layers, controls, custom SVG icons, and map utilities.
 */

window.ErpKopdesMap = (function() {
    'use strict';

    // ── Tile Layer Definitions ──────────────────────────────────────────
    function getTileLayers() {
        const osmStreet = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });

        const esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        });

        const esriRef = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: ''
        });

        // Hybrid Satellite layer (Imagery + Road Labels)
        const satHybrid = L.layerGroup([esriSat, esriRef]);

        const esriTopo = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ, TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China'
        });

        const cartoDark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
        });

        return {
            street: osmStreet,
            satellite: satHybrid,
            topo: esriTopo,
            dark: cartoDark
        };
    }

    function getBaseMaps() {
        const layers = getTileLayers();
        return {
            "🗺️ Peta Jalan": layers.street,
            "🛰️ Satelit HD": layers.satellite,
            "🏔️ Medan / Topo": layers.topo,
            "🌙 Mode Gelap": layers.dark
        };
    }

    // ── Custom SVG Pin Icon ─────────────────────────────────────────────
    function createKopdesIcon(color = '#cc0000', badge = null) {
        const badgeSvg = badge ? `
            <circle cx="28" cy="8" r="8" fill="#111827" stroke="#ffffff" stroke-width="1.5"/>
            <text x="28" y="11" font-size="9" font-weight="bold" fill="#ffffff" text-anchor="middle">${badge}</text>
        ` : '';

        const svgHtml = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 46" width="36" height="46" style="filter: drop-shadow(0 3px 6px rgba(0,0,0,0.3));">
                <path d="M18 0C8.059 0 0 8.059 0 18c0 13.5 18 28 18 28s18-14.5 18-28C36 8.059 27.941 0 18 0z" fill="${color}"/>
                <circle cx="18" cy="17" r="8.5" fill="#ffffff"/>
                <path d="M18 12.5l2.5 5.5h-5z" fill="${color}"/>
                ${badgeSvg}
            </svg>
        `;

        return L.divIcon({
            className: 'custom-kopdes-pin',
            html: svgHtml,
            iconSize: [36, 46],
            iconAnchor: [18, 46],
            popupAnchor: [0, -42]
        });
    }

    // ── Map Setup & Controls Standardizer ──────────────────────────────
    function initMap(containerId, center = [-2.5489, 118.0149], zoom = 5, defaultLayerName = "🗺️ Peta Jalan") {
        const baseMaps = getBaseMaps();
        const initialLayer = baseMaps[defaultLayerName] || baseMaps["🗺️ Peta Jalan"];

        const map = L.map(containerId, {
            center: center,
            zoom: zoom,
            layers: [initialLayer],
            zoomControl: true
        });

        // 1. Add Native Layer Switcher Control
        L.control.layers(baseMaps, null, { position: 'topright', collapsed: true }).addTo(map);

        // 2. Add Scale Control
        L.control.scale({ imperial: false, metric: true, position: 'bottomleft' }).addTo(map);

        // 3. Add Custom Locate Me Button Control
        const LocateControl = L.Control.extend({
            options: { position: 'topleft' },
            onAdd: function() {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                container.style.backgroundColor = '#ffffff';
                container.style.width = '34px';
                container.style.height = '34px';
                container.style.display = 'flex';
                container.style.alignItems = 'center';
                container.style.justifyContent = 'center';
                container.style.cursor = 'pointer';
                container.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
                container.title = 'Lokasi Saya saat ini';
                container.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="7"/>
                        <line x1="12" y1="2" x2="12" y2="5"/>
                        <line x1="12" y1="19" x2="12" y2="22"/>
                        <line x1="2" y1="12" x2="5" y2="12"/>
                        <line x1="19" y1="12" x2="22" y2="12"/>
                    </svg>
                `;
                container.onclick = function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    if (navigator.geolocation) {
                        container.style.opacity = '0.5';
                        navigator.geolocation.getCurrentPosition(
                            pos => {
                                container.style.opacity = '1';
                                const lat = pos.coords.latitude;
                                const lng = pos.coords.longitude;
                                map.flyTo([lat, lng], 16, { animate: true, duration: 1.2 });
                                
                                // User location pulse marker
                                const userMarker = L.circleMarker([lat, lng], {
                                    radius: 9,
                                    fillColor: '#2563eb',
                                    color: '#ffffff',
                                    weight: 3,
                                    opacity: 1,
                                    fillOpacity: 0.9
                                }).addTo(map);
                                userMarker.bindPopup("<strong>📍 Lokasi Anda Saat Ini</strong>").openPopup();
                            },
                            err => {
                                container.style.opacity = '1';
                                alert('Tidak dapat mengakses lokasi GPS. Pastikan izin lokasi aktif.');
                            },
                            { timeout: 8000, enableHighAccuracy: true }
                        );
                    }
                };
                return container;
            }
        });
        map.addControl(new LocateControl());

        // 4. Add Fullscreen Toggle Control
        const FullscreenControl = L.Control.extend({
            options: { position: 'topleft' },
            onAdd: function() {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-fullscreen');
                container.style.backgroundColor = '#ffffff';
                container.style.width = '34px';
                container.style.height = '34px';
                container.style.display = 'flex';
                container.style.alignItems = 'center';
                container.style.justifyContent = 'center';
                container.style.cursor = 'pointer';
                container.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
                container.title = 'Layar Penuh (Fullscreen)';
                container.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                    </svg>
                `;
                container.onclick = function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    const mapEl = document.getElementById(containerId);
                    if (!document.fullscreenElement) {
                        if (mapEl.requestFullscreen) mapEl.requestFullscreen();
                        else if (mapEl.webkitRequestFullscreen) mapEl.webkitRequestFullscreen();
                    } else {
                        if (document.exitFullscreen) document.exitFullscreen();
                    }
                };
                return container;
            }
        });
        map.addControl(new FullscreenControl());

        return {
            map: map,
            baseMaps: baseMaps
        };
    }

    return {
        getTileLayers: getTileLayers,
        getBaseMaps: getBaseMaps,
        createKopdesIcon: createKopdesIcon,
        initMap: initMap
    };
})();
