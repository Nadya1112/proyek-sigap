<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran Wilayah & Kompleks - SIGAP</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
        body { margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
        #map { height: 100vh; width: 100%; }
        ul { list-style: none; padding: 0; margin: 0; }
        
        /* Panel Pengaturan */
        .pengaturan-panel {
            position: absolute; top: 80px; right: 20px; z-index: 1000;
            width: 280px; background: rgba(255, 255, 255, 0.95); /* Ganti putih biar pastel lebih keluar */
            color: #333; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            padding: 15px; display: none;
        }
        .pengaturan-panel h4 { margin-top: 0; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee; font-size: 1.1em; font-weight: 700; }
        .pengaturan-panel h5 { margin-top: 15px; margin-bottom: 10px; color: #555; font-size: 0.9em; text-transform: uppercase; letter-spacing: 1px; }
        .pengaturan-panel li { display: flex; align-items: center; margin-bottom: 8px; font-size: 0.95em; }
        .pengaturan-panel input[type="checkbox"] { margin-right: 10px; cursor: pointer; accent-color: #05c205; }
        .pengaturan-panel label { flex-grow: 1; cursor: pointer; }
        .legend-color { display: inline-block; width: 20px; height: 20px; border-radius: 5px; margin-left: 10px; border: 1px solid #ddd; }
        .panel-close-btn { position: absolute; top: 10px; right: 15px; background: none; border: none; color: #999; font-size: 24px; cursor: pointer; }
        .panel-close-btn:hover { color: #333; }
        
        .leaflet-control-settings a {
            font-size: 1.4em; color: #333; width: 34px; height: 34px; line-height: 34px; text-align: center; background: #fff;
            border-radius: 4px; box-shadow: 0 1px 5px rgba(0,0,0,0.65); cursor: pointer;
        }
        .leaflet-top.leaflet-left { top: 80px; } 
        .leaflet-control-zoom a { width: 25px !important; height: 25px !important; line-height: 25px !important; font-size: 16px !important; }
        
        #map-header-card {
            position: absolute; top: 20px; left: 20px; z-index: 1001;
            background: white;
            color: #333; padding: 10px 20px; border-radius: 50px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            font-weight: bold; font-size: 1.1em; text-transform: uppercase;
            pointer-events: none; border: 2px solid #05c205;
        }

        /* Popup Styles */
        .leaflet-popup-content-wrapper { background: #fff; color: #333; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); }
        .leaflet-popup-tip-container .leaflet-popup-tip { background: #fff; }
        .leaflet-popup-content { margin: 0 !important; padding: 0; width: auto !important; font-size: 14px; line-height: 1.6; }
        .custom-popup-title { padding: 12px 15px; font-weight: bold; font-size: 15px; border-bottom: 1px solid #f0f0f0; color: #05c205; }
        .leaflet-popup-close-button { position: absolute; top: 8px; right: 10px; padding: 5px; border: none; background: none; font-size: 20px; color: #aaa; }
        .custom-popup-info { padding: 10px 15px; border-bottom: 1px solid #f0f0f0; }
        .custom-popup-button-div { padding: 15px; text-align: center; }
        .custom-popup-button { background-color: #0d6efd; color: white; border: none; padding: 8px 20px; border-radius: 20px; width: 100%; cursor: pointer; font-size: 13px; font-weight: 600; transition: 0.2s; }
        .custom-popup-button:hover { background-color: #0b5ed7; transform: scale(1.02); }

        /* Modal Detail */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); z-index: 2000;
            display: flex; justify-content: center; align-items: center;
            backdrop-filter: blur(4px);
        }
        .modal-content {
            background: #fff; width: 90%; max-width: 550px;
            border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            overflow: hidden; animation: slideUp 0.3s ease-out; display: flex; flex-direction: column;
        }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .modal-header {
            background: #05c205; color: white; padding: 15px 25px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-header h3 { margin: 0; font-size: 1.3rem; font-weight: 700; }
        .close-modal { background: none; border: none; color: white; font-size: 28px; cursor: pointer; }
        .modal-body { padding: 0; max-height: 70vh; overflow-y: auto; }
        
        .detail-table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
        .detail-table tr:nth-child(even) { background-color: #f8f9fa; }
        .detail-table th { text-align: left; padding: 12px 20px; width: 35%; color: #666; font-weight: 600; border-bottom: 1px solid #eee; }
        .detail-table td { padding: 12px 20px; color: #333; border-bottom: 1px solid #eee; }
        .divider-row td { background-color: #f0f0f0; height: 8px; padding: 0; border: none; }
        .modal-footer { padding: 15px 25px; text-align: right; border-top: 1px solid #eee; background: #fff; }
        .btn-tutup { background: #6c757d; color: white; border: none; padding: 8px 25px; border-radius: 50px; cursor: pointer; font-weight: 600; }
    </style>
</head>
<body>

    <div id="map-header-card">PETA - SEBARAN</div>

    <div class="pengaturan-panel" id="pengaturan-panel">
        <button class="panel-close-btn" id="panel-close-btn">&times;</button>
        <h4>Pengaturan Layer</h4>
        <h5>Kecamatan</h5>
        <ul id="kecamatan-filter-list">
            @if(isset($kecamatans) && !$kecamatans->isEmpty())
                @foreach ($kecamatans as $kecamatan)
                    <li>
                        <input type="checkbox" class="kecamatan-checkbox" value="{{ $kecamatan->nama_kecamatan }}" checked>
                        <label>{{ $kecamatan->nama_kecamatan }}</label>
                        <span class="legend-color" data-kecamatan="{{ $kecamatan->nama_kecamatan }}"></span>
                    </li>
                @endforeach
            @else <li>Data kecamatan tidak ditemukan.</li> @endif
        </ul>
        <h5>Sebaran Komplek</h5>
        <ul id="kompleks-filter-list">
            <li>
                <input type="checkbox" id="kompleks-checkbox" value="kompleks" checked>
                <label for="kompleks-checkbox">Sebaran Komplek</label>
                <span class="legend-color" style="background-color: #05c205; border: 2px solid white; box-shadow: 0 0 2px #999;"></span>
            </li>
        </ul>
    </div>

    <div id="map"></div>

    <div id="modal-detail" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Detail Komplek</h3>
                <button class="close-modal" onclick="tutupModal()">&times;</button>
            </div>
            <div class="modal-body">
                <table class="detail-table">
                    <tr><th>Pengembang</th><td id="d-pengembang">-</td></tr>
                    <tr><th>Alamat</th><td id="d-alamat">-</td></tr>
                    <tr><th>Kelurahan</th><td id="d-kelurahan">-</td></tr>
                    <tr><th>Kecamatan</th><td id="d-kecamatan">-</td></tr>
                    
                    <tr class="divider-row"><td colspan="2"></td></tr>
                    
                    <tr><th>Jumlah Sertifikat</th><td id="d-sertifikat">0</td></tr>
                    <tr><th>Jumlah Unit</th><td id="d-unit">0</td></tr>
                    <tr><th>Status Aset</th><td id="d-status">-</td></tr>
                    
                    <tr class="divider-row"><td colspan="2"></td></tr>
                    
                    <tr><th>Fasilitas Ibadah</th><td id="d-ibadah">-</td></tr>
                    <tr><th>Fasilitas Umum</th><td id="d-umum">-</td></tr>
                    <tr><th>Fasilitas Pendidikan</th><td id="d-pendidikan">-</td></tr>
                    <tr><th>Fasilitas Kesehatan</th><td id="d-kesehatan">-</td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn-tutup" onclick="tutupModal()">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // --- 1. Inisialisasi Peta ---
        const map = L.map('map').setView([-3.32, 114.59], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>' 
        }).addTo(map);

        // --- 2. Variabel & Layer Group ---
        const kecamatanLayerGroups = {};
        const kompleksLayerGroup = L.layerGroup().addTo(map); 
        let allKelurahanLayer = null;
        let displayedKelurahanLayer = null;
        let kompleksDataStore = {};

        // =====================================================================
        // [KONFIGURASI] WARNA PASTEL KECAMATAN
        // =====================================================================
        const warnaPastelKecamatan = {
            "Banjarmasin Barat":   "#FFB7B2", // Pastel Pink Salmon
            "Banjarmasin Selatan": "#FFDAC1", // Pastel Peach/Oranye Lembut
            "Banjarmasin Tengah":  "#FFFFB5", // Pastel Kuning
            "Banjarmasin Timur":   "#B5EAD7", // Pastel Mint Hijau
            "Banjarmasin Utara":   "#C7CEEA"  // Pastel Ungu/Biru Muda
        };
        // =====================================================================

        // Icon Rumah Hijau Custom
        const GreenHouseIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="
                background-color: white;
                border: 2px solid #05c205;
                width: 32px; height: 32px;
                border-radius: 50%;
                display: flex; justify-content: center; align-items: center;
                box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
                <i class="fa-solid fa-house" style="color: #05c205; font-size: 16px;"></i>
            </div>`,
            iconSize: [32, 32], iconAnchor: [16, 16], popupAnchor: [0, -18]
        });

        // --- 3. Muat Data KECAMATAN (Dengan Warna Pastel) ---
        fetch('/api/kecamatan')
            .then(response => response.json())
            .then(data => {
                if (data && data.features) {
                    data.features.forEach(feature => {
                        const namaKecamatan = feature.properties.nama;
                        
                        // [LOGIKA] Ambil warna pastel, jika tidak ada pakai default
                        const warnaFinal = warnaPastelKecamatan[namaKecamatan] || feature.properties.warna || '#ccc';
                        
                        // [LOGIKA] Update warna kotak legend di sidebar agar sinkron
                        const legendSpan = document.querySelector(`.legend-color[data-kecamatan="${namaKecamatan}"]`);
                        if(legendSpan) {
                            legendSpan.style.backgroundColor = warnaFinal;
                        }

                        const layerGroup = L.layerGroup();
                        L.geoJSON(feature, {
                            style: { 
                                color: "#666",        // Garis tepi abu tua (supaya rapi)
                                weight: 1.5,          // Tebal garis
                                fillColor: warnaFinal, // Warna Pastel dari variabel
                                fillOpacity: 0.7       // Transparansi enak dilihat
                            },
                            onEachFeature: (f, l) => {
                                l.bindPopup(`
                                    <div style="text-align:center; font-weight:bold; color:#555;">
                                        Kecamatan<br>
                                        <span style="color:#000; font-size:14px;">${f.properties.nama}</span>
                                    </div>
                                `);
                                l.on('click', function() { handleKecamatanClick(f.properties.nama); });
                            }
                        }).addTo(layerGroup);
                        kecamatanLayerGroups[namaKecamatan] = layerGroup;
                        layerGroup.addTo(map);
                    });
                }
            });

        // --- 4. Muat Data KELURAHAN ---
        fetch('/api/kelurahan')
            .then(response => response.json())
            .then(data => {
                if (data && data.geojson) {
                    allKelurahanLayer = L.geoJSON(data.geojson, { 
                        style: { color: "#555", weight: 1, fillColor: "#fff", fillOpacity: 0.1, dashArray: '4, 4' },
                        onEachFeature: (f, l) => {
                             l.bindPopup(`<strong>Kelurahan:</strong> ${f.properties.nama_kelurahan}`);
                        }
                    });
                    allKelurahanLayer.addTo(map);
                }
            });

        // --- 5. Muat Data KOMPLEKS ---
        fetch('/api/kompleks')
            .then(response => response.json())
            .then(data => {
                if (data && data.features) {
                    L.geoJSON(data, {
                        pointToLayer: (feature, latlng) => {
                            return L.marker(latlng, { icon: GreenHouseIcon }); 
                        },
                        onEachFeature: function(feature, layer) {
                            const props = feature.properties;
                            kompleksDataStore[props.id] = props;

                            if (props) {
                                const popupContent = `
                                <div style="font-family: sans-serif; min-width: 220px; text-align: center;">
                                    <h4 style="margin:0 0 5px 0; color:#05c205; font-size:14px; border-bottom:1px solid #eee; padding-bottom:5px;">
                                        ${props.nama_perumahan}
                                    </h4>
                                    <div style="font-size:11px; color:#666; margin-bottom:10px;">
                                        <i class="fa-solid fa-building"></i> ${props.nama_pengembang}
                                    </div>
                                    <button onclick="lihatDetail(${props.id})" class="custom-popup-button">
                                        Lihat Detail
                                    </button>
                                </div>`;
                                layer.bindPopup(popupContent, { offset: [0, -15] }); 
                            }
                        }
                    }).addTo(kompleksLayerGroup);
                }
            })
            .catch(error => console.error('Error fetching data kompleks:', error));


        // --- 6. Logika Peta Lainnya ---
        function handleKecamatanClick(namaKecamatanDiKlik) {
            if (displayedKelurahanLayer && map.hasLayer(displayedKelurahanLayer)) {
                map.removeLayer(displayedKelurahanLayer);
            }
            if (!allKelurahanLayer) return;

            // Style highlight kelurahan (Pastel Merah)
            const styleKelurahanBaru = { weight: 2, color: '#FF6961', dashArray: '0', fillColor: '#fff', fillOpacity: 0.1 };
            displayedKelurahanLayer = L.layerGroup();
            let ditemukan = 0;

            allKelurahanLayer.eachLayer(function(layer) {
                let namaKelurahanKecamatan = layer.feature.properties.nama_kecamatan;
                if (namaKelurahanKecamatan && namaKecamatanDiKlik && 
                    namaKelurahanKecamatan.trim().toLowerCase() === namaKecamatanDiKlik.trim().toLowerCase()) {
                    ditemukan++;
                    let highlighted = L.geoJSON(layer.feature, { 
                         style: styleKelurahanBaru,
                         onEachFeature: (f, l) => l.bindPopup(layer.getPopup().getContent())
                    }); 
                    highlighted.addTo(displayedKelurahanLayer);
                }
            });

            if (ditemukan > 0) {
                displayedKelurahanLayer.addTo(map);
                displayedKelurahanLayer.bringToFront(); 
            }
        }
        
        map.on('click', function(e) {
             if (displayedKelurahanLayer && map.hasLayer(displayedKelurahanLayer)) {
                 if (!e.originalEvent.target.closest('.leaflet-popup-content-wrapper')) {
                     map.removeLayer(displayedKelurahanLayer);
                     displayedKelurahanLayer = null;
                 }
             }
        });

        // --- 7. Panel Pengaturan ---
        L.Control.Settings = L.Control.extend({
              onAdd: (map) => {
                  const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-settings');
                  container.innerHTML = `<a href="#" title="Pengaturan Layer" role="button"><i class="fa-solid fa-layer-group"></i></a>`;
                  L.DomEvent.disableClickPropagation(container).on(container, 'click', () => { document.getElementById('pengaturan-panel').style.display = 'block'; });
                  return container;
              }
        });
        new L.Control.Settings({ position: 'topright' }).addTo(map);

        document.getElementById('panel-close-btn').addEventListener('click', () => { document.getElementById('pengaturan-panel').style.display = 'none'; });

        document.getElementById('kecamatan-filter-list').addEventListener('change', function(e) {
              if (e.target && e.target.matches('.kecamatan-checkbox')) {
                  const group = kecamatanLayerGroups[e.target.value];
                  if (group) { e.target.checked ? map.addLayer(group) : map.removeLayer(group); }
              }
        });

        document.getElementById('kompleks-checkbox').addEventListener('change', function(e) {
            e.target.checked ? map.addLayer(kompleksLayerGroup) : map.removeLayer(kompleksLayerGroup);
        });

        // --- FUNGSI MODAL ---
        function lihatDetail(id) {
            const data = kompleksDataStore[id];
            if (!data) { alert("Data detail belum termuat."); return; }

            document.getElementById('modal-title').innerText = data.nama_perumahan;
            document.getElementById('d-pengembang').innerText = data.nama_pengembang;
            document.getElementById('d-alamat').innerText = data.alamat || '-';
            document.getElementById('d-kelurahan').innerText = data.kelurahan;
            document.getElementById('d-kecamatan').innerText = data.kecamatan;
            
            document.getElementById('d-sertifikat').innerText = data.jumlah_sertifikat;
            document.getElementById('d-unit').innerText = data.jumlah_unit;
            document.getElementById('d-status').innerText = data.status_aset;
            
            document.getElementById('d-ibadah').innerText = data.fasilitas_ibadah;
            document.getElementById('d-umum').innerText = data.fasilitas_umum;
            document.getElementById('d-pendidikan').innerText = data.fasilitas_pendidikan;
            document.getElementById('d-kesehatan').innerText = data.fasilitas_kesehatan;

            map.closePopup(); 
            document.getElementById('modal-detail').style.display = 'flex';
        }

        function tutupModal() { document.getElementById('modal-detail').style.display = 'none'; }
        document.getElementById('modal-detail').addEventListener('click', function(e) { if (e.target === this) tutupModal(); });

    </script>
</body> 
</html>