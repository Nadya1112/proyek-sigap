<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran - SIGAP KOMPLEK</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --sigap-orange: #F97316;
            --sigap-dark-orange: #ea580c;
            --sigap-gradient: linear-gradient(135deg, #F59E0B 0%, #F97316 100%);
            --text-dark: #333333;
            --text-grey: #666666;
            --border-color: #e5e7eb;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            overflow: hidden;
        }

        #map {
            height: 100vh;
            width: 100%;
            z-index: 1;
        }

        /* --- UI: TOMBOL & HEADER --- */
        .floating-btn {
            position: absolute;
            top: 24px;
            z-index: 1001;
            width: 44px;
            height: 44px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            color: var(--sigap-orange);
            text-decoration: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .floating-btn:hover {
            background: var(--sigap-orange);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-back {
            left: 24px;
        }

        .btn-layer {
            right: 24px;
        }

        #map-header-card {
            position: absolute;
            top: 24px;
            left: 80px;
            z-index: 1001;
            background: rgba(255, 255, 255, 0.95);
            padding: 0 20px;
            height: 44px;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(5px);
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s;
        }

        #map-header-card:hover {
            transform: translateY(-2px);
        }

        .header-logo {
            font-weight: 800;
            font-size: 15px;
            color: var(--text-dark);
        }

        .header-logo span {
            color: var(--sigap-orange) !important;
        }

        .header-subtitle {
            font-size: 12px;
            color: var(--text-grey);
            padding-left: 10px;
            border-left: 1px solid #ddd;
            height: 20px;
            line-height: 20px;
        }

        /* --- UI: PANEL LAYER (PUTIH) --- */
        .pengaturan-panel {
            position: absolute;
            top: 80px;
            right: 24px;
            z-index: 1005;
            width: 280px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: none;
            animation: fadeIn 0.3s ease;
            max-height: 80vh;
            overflow-y: auto;
            backdrop-filter: blur(4px);
            border: 1px solid #eee;
            color: #333;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }

        .panel-header h4 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .close-panel {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 18px;
            color: #999;
        }

        .close-panel:hover {
            color: var(--sigap-orange);
        }

        /* SEARCH BOX */
        .search-container {
            position: relative;
            margin-bottom: 15px;
        }

        .search-input {
            width: 100%;
            padding: 10px 35px 10px 12px;
            box-sizing: border-box;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            color: #333;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--sigap-orange);
            background: #fff;
        }

        .search-icon {
            position: absolute;
            right: 12px;
            top: 10px;
            color: #aaa;
            font-size: 13px;
        }

        #search-results {
            list-style: none;
            padding: 0;
            margin: 5px 0 0 0;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            position: absolute;
            width: 100%;
            z-index: 10;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            border: 1px solid #eee;
        }

        #search-results li {
            padding: 8px 12px;
            cursor: pointer;
            color: #333;
            font-size: 12px;
            border-bottom: 1px solid #f5f5f5;
        }

        #search-results li:hover {
            background: #fff7ed;
            color: var(--sigap-orange);
            font-weight: 600;
        }

        /* SEPARATOR & GLOBAL CHECK */
        .layer-separator {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 15px;
            margin-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 2px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .layer-separator:first-of-type {
            margin-top: 5px;
        }

        .check-all-wrapper {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            text-transform: none;
            color: var(--sigap-orange);
        }

        .check-all-wrapper input {
            cursor: pointer;
            accent-color: var(--sigap-orange);
        }

        .global-check {
            background: #fff7ed;
            border: 1px solid #ffedd5;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .global-check label {
            font-weight: 700;
            color: var(--sigap-orange);
            font-size: 13px;
            cursor: pointer;
            flex-grow: 1;
            margin-left: 8px;
        }

        .global-check input {
            accent-color: var(--sigap-orange);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        /* LAYER ITEMS */
        .layer-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 13px;
            color: #444;
            font-weight: 500;
        }

        .layer-item input {
            margin-right: 10px;
            accent-color: var(--sigap-orange);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .legend-box {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            margin-left: auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* LABEL KECAMATAN (DOFF) */
        .label-kecamatan {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            color: #fff !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
            text-transform: uppercase;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 1px;
            text-align: center;
            opacity: 0.9;
        }

        /* CLUSTER (ORANYE) */
        .cluster-sigap {
            background: var(--sigap-gradient);
            border: 2px solid #fff;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 14px;
            text-align: center;
            border-radius: 50%;
            line-height: 40px;
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.5);
        }

        /* --- IKON RUMAH CUSTOM (AGAR TIDAK HITAM) --- */
        .icon-rumah-wrapper {
            background: #fff;
            border: 2px solid var(--sigap-orange);
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s;
        }

        .icon-rumah-wrapper:hover {
            transform: scale(1.3);
            background: var(--sigap-orange);
            border-color: #fff;
            z-index: 999;
        }

        .icon-rumah-wrapper i {
            color: var(--sigap-orange);
            font-size: 14px;
        }

        .icon-rumah-wrapper:hover i {
            color: #fff;
        }

        /* POPUP & MODAL */
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .leaflet-popup-content {
            margin: 15px 20px;
            line-height: 1.6;
            min-width: 280px;
            font-family: 'Poppins', sans-serif;
        }

        .kelurahan-title {
            font-size: 16px;
            font-weight: 700;
            color: #40513B;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }

        .komplek-popup-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--sigap-orange);
            margin-bottom: 5px;
        }

        .popup-row {
            font-size: 13px;
            color: #333;
            margin-bottom: 4px;
            display: flex;
        }

        .popup-label {
            font-weight: 700;
            width: 90px;
            color: #444;
            flex-shrink: 0;
        }

        .popup-val {
            color: #555;
        }

        .btn-detail-popup {
            background: var(--sigap-gradient);
            color: #fff;
            border: none;
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
        }

        .btn-detail-popup:hover {
            opacity: 0.9;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 2000;
            display: none;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(2px);
        }

        .modal-box {
            background: #fff;
            width: 95%;
            max-width: 600px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            max-height: 90vh;
        }

        .modal-header {
            background: var(--sigap-gradient);
            padding: 15px 20px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .btn-close-modal {
            background: none;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
            opacity: 0.9;
        }

        .modal-body {
            padding: 0;
            overflow-y: auto;
            background: #fff;
        }

        .detail-row {
            display: flex;
            padding: 12px 20px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            width: 40%;
            color: #666;
            font-weight: 600;
        }

        .detail-value {
            width: 60%;
            color: #333;
            font-weight: 500;
        }

        .section-fasilitas {
            padding: 20px 20px 5px 20px;
            margin-top: 5px;
        }

        .text-fasilitas {
            color: var(--sigap-dark-orange);
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 2px solid var(--sigap-dark-orange);
            display: inline-block;
            padding-bottom: 2px;
            margin-bottom: 10px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            background: #f9f9f9;
            text-align: right;
        }

        .btn-grey-close {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-grey-close:hover {
            background: #5a6268;
        }

        /* Zoom Control */
        .leaflet-top.leaflet-left {
            margin-top: 85px;
            margin-left: 24px;
        }

        .leaflet-bar {
            border: none !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            border-radius: 8px !important;
            overflow: hidden;
        }

        .leaflet-bar a {
            background-color: #fff !important;
            color: var(--sigap-orange) !important;
            width: 34px !important;
            height: 34px !important;
            line-height: 34px !important;
            font-weight: 600 !important;
        }

        .leaflet-bar a:hover {
            background-color: #fff7ed !important;
        }
    </style>
</head>

<body>

    <a href="javascript:history.back()" class="floating-btn btn-back" title="Kembali">
        <i class="fa-solid fa-arrow-left"></i>
    </a>

    <a href="{{ route('sebaran') }}" id="map-header-card" title="Peta Sebaran Komplek">
        <div class="header-logo">SIGAP <span>KOMPLEK</span></div>
        <div class="header-subtitle">Peta Sebaran</div>
    </a>

    <div class="floating-btn btn-layer" onclick="togglePanel()"><i class="fa-solid fa-layer-group"></i></div>

    <div class="pengaturan-panel" id="panel-layer">
        <div class="panel-header">
            <h4>Filter & Cari</h4><button class="close-panel" onclick="togglePanel()">&times;</button>
        </div>

        <div class="search-container">
            <input type="text" id="search-input" class="search-input" placeholder="Cari nama komplek..."
                onkeyup="searchLocation()">
            <i class="fa-solid fa-search search-icon"></i>
            <ul id="search-results"></ul>
        </div>

        <div class="global-check">
            <input type="checkbox" id="check-global" checked onchange="toggleGlobal(this)">
            <label for="check-global">TAMPILKAN SEMUA DATA</label>
        </div>

        <div class="layer-separator">Tipe Peta</div>
        <div class="layer-item">
            <input type="radio" name="basemap" id="base-osm" checked onchange="switchBaseMap('osm')">
            <label for="base-osm">Standar (Jalan)</label>
        </div>
        <div class="layer-item">
            <input type="radio" name="basemap" id="base-sat" onchange="switchBaseMap('sat')">
            <label for="base-sat">Satelit</label>
        </div>

        <div class="layer-separator">
            <span>Kecamatan</span>
            <label class="check-all-wrapper" style="font-size:10px; display:flex; align-items:center; gap:4px;">
                <input type="checkbox" id="check-all-kecamatan" checked onchange="toggleAllKecamatan(this)"> Kec. Saja
            </label>
        </div>
        <div id="list-kecamatan"></div>

        <div class="layer-separator">Sebaran Komplek</div>
        <div class="layer-item">
            <input type="checkbox" id="check-komplek" checked>
            <label for="check-komplek">Titik Komplek</label>
            <div class="legend-box" style="background:var(--sigap-orange)"></div>
        </div>

        <div class="layer-separator">Lainnya</div>
        <div class="layer-item">
            <input type="checkbox" id="check-kelurahan" checked>
            <label for="check-kelurahan">Batas Kelurahan</label>
            <div class="legend-box" style="border:1px dashed #bbb"></div>
        </div>
    </div>

    <div id="map"></div>

    <div id="modal-detail" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3 id="m-title">Nama Komplek</h3>
                <button class="btn-close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-row">
                    <div class="detail-label">Alamat</div>
                    <div class="detail-value" id="m-alamat">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Kelurahan</div>
                    <div class="detail-value" id="m-kelurahan">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Sertifikat</div>
                    <div class="detail-value" id="m-sertifikat">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Unit</div>
                    <div class="detail-value" id="m-unit">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status Aset</div>
                    <div class="detail-value" id="m-status">-</div>
                </div>
                <div class="section-fasilitas"><span class="text-fasilitas">FASILITAS</span></div>
                <div class="detail-row">
                    <div class="detail-label">Ibadah</div>
                    <div class="detail-value" id="m-ibadah">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Umum</div>
                    <div class="detail-value" id="m-umum">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Pendidikan</div>
                    <div class="detail-value" id="m-pendidikan">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Kesehatan</div>
                    <div class="detail-value" id="m-kesehatan">-</div>
                </div>
            </div>
            <div class="modal-footer"><button class="btn-grey-close" onclick="closeModal()">Tutup</button></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    <script>
        // BASE LAYERS
        const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'SIGAP',
            maxZoom: 19
        });
        const satLayer = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });

        const map = L.map('map', {
            zoomControl: false,
            layers: [osmLayer]
        }).setView([-3.32, 114.59], 13);
        L.control.zoom({
            position: 'topleft'
        }).addTo(map);

        map.createPane('paneKecamatan');
        map.getPane('paneKecamatan').style.zIndex = 390;
        map.createPane('paneKelurahan');
        map.getPane('paneKelurahan').style.zIndex = 450;
        map.createPane('paneKomplek');
        map.getPane('paneKomplek').style.zIndex = 650;

        const layers = {
            komplek: L.markerClusterGroup({
                iconCreateFunction: function(cluster) {
                    return L.divIcon({
                        html: '<div>' + cluster.getChildCount() + '</div>',
                        className: 'cluster-sigap',
                        iconSize: [40, 40]
                    });
                },
                pane: 'paneKomplek'
            }).addTo(map),
            kecamatan: {},
            kelurahan: L.layerGroup({
                pane: 'paneKelurahan'
            }).addTo(map)
        };

        const dataStore = {};
        const markersById = {};
        const searchableList = [];
        const colors = {
            "Banjarmasin Barat": "#F59E0B",
            "Banjarmasin Selatan": "#10B981",
            "Banjarmasin Tengah": "#EF4444",
            "Banjarmasin Timur": "#3B82F6",
            "Banjarmasin Utara": "#8B5CF6"
        };

        // IKON RUMAH ORANYE
        const iconRumah = L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="icon-rumah-wrapper"><i class="fa-solid fa-house"></i></div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 14],
            popupAnchor: [0, -16]
        });

        // --- SWITCH BASEMAP ---
        window.switchBaseMap = (type) => {
            if (type === 'osm') {
                map.addLayer(osmLayer);
                map.removeLayer(satLayer);
            } else {
                map.addLayer(satLayer);
                map.removeLayer(osmLayer);
            }
        }

        // --- FETCH DATA ---
        Promise.all([
            fetch('{{ url('/api/kompleks') }}').then(r => r.json()).then(d => {
                if (d.features) {
                    const geoJsonLayer = L.geoJSON(d, {
                        pane: 'paneKomplek',
                        pointToLayer: (f, latlng) => {
                            // Pakai iconRumah yang sudah didefinisikan
                            const marker = L.marker(latlng, {
                                icon: iconRumah
                            });
                            markersById[f.properties.id] = marker;
                            return marker;
                        },
                        onEachFeature: (f, l) => {
                            const p = f.properties;
                            dataStore[p.id] = p;
                            searchableList.push({
                                id: p.id,
                                name: p.nama_perumahan,
                                lat: f.geometry.coordinates[1],
                                lng: f.geometry.coordinates[0]
                            });

                            const popupContent = `
                                <div class="komplek-popup-title">${p.nama_perumahan}</div>
                                <div style="font-size:12px; color:#666;">
                                    <strong>Pengembang:</strong><br>
                                    ${(p.nama_pengembang || '-').replace(/;/g, '.')}
                                </div>
                                <button class="btn-detail-popup" onclick="openDetail(${p.id})">Lihat Detail</button>
                            `;
                            l.bindPopup(popupContent, {
                                maxWidth: 280
                            });
                        }
                    });
                    layers.komplek.addLayer(geoJsonLayer);
                }
            }),

            fetch('{{ url('/api/kelurahan') }}').then(r => r.json()).then(d => {
                if (d.geojson && d.geojson.features) {
                    L.geoJSON(d.geojson, {
                        pane: 'paneKelurahan',
                        style: {
                            color: '#E2E8F0',
                            weight: 1.2,
                            dashArray: '4, 4',
                            fillOpacity: 0,
                            opacity: 0.8
                        },
                        filter: function(feature, layer) {
                            return feature.geometry.type === "Polygon" || feature.geometry.type ===
                                "MultiPolygon";
                        },
                        onEachFeature: (f, l) => {
                            const props = f.properties;
                            const content = `
                                <div class="kelurahan-title">Kelurahan: ${props.nama_kelurahan}</div>
                                <div class="popup-row"><div class="popup-label">Kota:</div><div class="popup-val">Banjarmasin</div></div>
                                <div class="popup-row"><div class="popup-label">Kecamatan:</div><div class="popup-val">${props.nama_kecamatan || '-'}</div></div>
                                <div class="popup-row"><div class="popup-label">Sumber:</div><div class="popup-val">Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin</div></div>
                                <button class="btn-detail-popup" style="background: #64748b; margin-top: 15px;" onclick="map.closePopup()">Selesai</button>
                            `;
                            l.bindPopup(content, {
                                maxWidth: 320
                            });
                            l.on('mouseover', e => e.target.setStyle({
                                weight: 2,
                                color: '#fff',
                                opacity: 1
                            }));
                            l.on('mouseout', e => e.target.setStyle({
                                weight: 1.2,
                                color: '#E2E8F0',
                                opacity: 0.8
                            }));
                        }
                    }).addTo(layers.kelurahan);
                }
            }),

            fetch('{{ url('/api/kecamatan') }}').then(r => r.json()).then(d => {
                const ul = document.getElementById('list-kecamatan');
                if (d.features) d.features.forEach(f => {
                    const nama = f.properties.nama;
                    const warna = colors[nama] || '#999';

                    layers.kecamatan[nama] = L.geoJSON(f, {
                        pane: 'paneKecamatan',
                        style: {
                            color: warna,
                            weight: 2,
                            opacity: 1,
                            fillColor: warna,
                            fillOpacity: 0.75
                        },
                        onEachFeature: (feature, layer) => {
                            layer.bindTooltip(nama, {
                                permanent: true,
                                direction: "center",
                                className: "label-kecamatan"
                            });
                        }
                    });

                    const div = document.createElement('div');
                    div.className = 'layer-item';
                    div.innerHTML = `
                        <input type="checkbox" value="${nama}" checked onchange="toggleKecamatan(this); checkMasterState();">
                        <label>${nama}</label>
                        <div class="legend-box" style="background:${warna}"></div>
                    `;
                    ul.appendChild(div);
                    map.addLayer(layers.kecamatan[nama]);
                });
            })
        ]).then(() => {
            // Setelah semua data berhasil dimuat, cek parameter URL
            checkUrlForDetail();
        }).catch(err => {
            console.error("Gagal memuat data:", err);
        });

        function checkUrlForDetail() {
            const urlParams = new URLSearchParams(window.location.search);
            const komplekId = urlParams.get('location');

            if (komplekId && dataStore[komplekId]) {
                const marker = markersById[komplekId];
                if (marker) {
                    map.flyTo(marker.getLatLng(), 18);
                    layers.komplek.zoomToShowLayer(marker, function() {
                        marker.openPopup();
                    });
                }
            }
        }

        function searchLocation() {
            const input = document.getElementById('search-input').value.toLowerCase();
            const results = document.getElementById('search-results');
            results.innerHTML = '';
            if (input.length < 3) {
                results.style.display = 'none';
                return;
            }
            const matches = searchableList.filter(item => item.name.toLowerCase().includes(input));
            if (matches.length > 0) {
                results.style.display = 'block';
                matches.slice(0, 5).forEach(item => {
                    const li = document.createElement('li');
                    li.innerHTML = `<i class="fa-solid fa-house"></i> ${item.name}`;
                    li.onclick = () => {
                        map.flyTo([item.lat, item.lng], 18);
                        const marker = markersById[item.id];
                        if (marker) {
                            layers.komplek.zoomToShowLayer(marker, function() {
                                marker.openPopup();
                            });
                        }
                        results.style.display = 'none';
                    };
                    results.appendChild(li);
                });
            } else {
                results.style.display = 'none';
            }
        }

        function togglePanel() {
            const p = document.getElementById('panel-layer');
            p.style.display = (p.style.display === 'none' ? 'block' : 'none');
        }

        window.toggleKecamatan = (cb) => {
            const l = layers.kecamatan[cb.value];
            if (l) cb.checked ? map.addLayer(l) : map.removeLayer(l);
        }

        // --- CHECK ALL LOGIC ---
        window.toggleGlobal = (source) => {
            const isChecked = source.checked;

            const chkKomplek = document.getElementById('check-komplek');
            chkKomplek.checked = isChecked;
            chkKomplek.dispatchEvent(new Event('change'));

            const chkKelurahan = document.getElementById('check-kelurahan');
            chkKelurahan.checked = isChecked;
            chkKelurahan.dispatchEvent(new Event('change'));

            const chkAllKec = document.getElementById('check-all-kecamatan');
            chkAllKec.checked = isChecked;
            toggleAllKecamatan(chkAllKec);
        }

        window.toggleAllKecamatan = (source) => {
            const checkboxes = document.querySelectorAll('#list-kecamatan input[type="checkbox"]');
            checkboxes.forEach(cb => {
                if (cb.checked !== source.checked) {
                    cb.checked = source.checked;
                    toggleKecamatan(cb);
                }
            });
            updateGlobalState();
        }

        window.checkMasterState = () => {
            const checkboxes = document.querySelectorAll('#list-kecamatan input[type="checkbox"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            document.getElementById('check-all-kecamatan').checked = allChecked;
            updateGlobalState();
        }

        window.updateGlobalState = () => {
            const keca = document.getElementById('check-all-kecamatan').checked;
            const komp = document.getElementById('check-komplek').checked;
            const kelu = document.getElementById('check-kelurahan').checked;
            document.getElementById('check-global').checked = (keca && komp && kelu);
        }

        document.getElementById('check-kelurahan').onchange = (e) => {
            e.target.checked ? map.addLayer(layers.kelurahan) : map.removeLayer(layers.kelurahan);
            updateGlobalState();
        };
        document.getElementById('check-komplek').onchange = (e) => {
            e.target.checked ? map.addLayer(layers.komplek) : map.removeLayer(layers.komplek);
            updateGlobalState();
        };

        window.openDetail = (id, zoomTo = false) => {
            const d = dataStore[id];
            if (!d) return;

            if (zoomTo) {
                const marker = markersById[id];
                if (marker) {
                    map.flyTo(marker.getLatLng(), 18);
                }
            }

            map.closePopup();
            document.getElementById('m-title').innerText = d.nama_perumahan;
            document.getElementById('m-alamat').innerText = (d.alamat || '-').replace(/;/g, '.');
            document.getElementById('m-kelurahan').innerText = (d.nama_kelurahan || '-').replace(/;/g, '.');
            document.getElementById('m-sertifikat').innerText = (d.jumlah_sertifikat != null ? d.jumlah_sertifikat :
                '-');
            document.getElementById('m-unit').innerText = (d.jumlah_unit != null ? d.jumlah_unit : '-');
            const status = d.status_aset || '-';
            let color = '#334155';
            if (status.includes('Sudah')) color = '#10B981';
            else if (status.includes('Belum')) color = '#EF4444';
            document.getElementById('m-status').innerHTML =
                `<span style="color:${color}; font-weight:700;">${status.replace(/;/g, '.')}</span>`;
            document.getElementById('m-ibadah').innerText = (d.fasilitas_ibadah || '-').replace(/;/g, '.');
            document.getElementById('m-umum').innerText = (d.fasilitas_umum || '-').replace(/;/g, '.');
            document.getElementById('m-pendidikan').innerText = (d.fasilitas_pendidikan || '-').replace(/;/g, '.');
            document.getElementById('m-kesehatan').innerText = (d.fasilitas_kesehatan || '-').replace(/;/g, '.');
            document.getElementById('modal-detail').style.display = 'flex';
        }
        window.closeModal = () => document.getElementById('modal-detail').style.display = 'none';
        document.getElementById('modal-detail').onclick = (e) => {
            if (e.target.id === 'modal-detail') closeModal();
        };
    </script>
</body>

</html>
