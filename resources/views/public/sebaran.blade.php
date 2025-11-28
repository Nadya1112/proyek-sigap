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
        
        /* PANEL PENGATURAN */
        .pengaturan-panel {
            position: absolute; top: 80px; right: 20px; z-index: 1000;
            width: 280px; background: rgba(50, 50, 50, 0.95); /* Tema Gelap */
            color: #fff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.4);
            padding: 15px; display: none;
        }
        .pengaturan-panel h4 { margin-top: 0; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #666; font-size: 1.1em; font-weight: 700; color: #eee; }
        .pengaturan-panel h5 { margin-top: 15px; margin-bottom: 10px; color: #aaa; font-size: 0.9em; text-transform: uppercase; letter-spacing: 1px; }
        .pengaturan-panel li { display: flex; align-items: center; margin-bottom: 8px; font-size: 0.95em; }
        .pengaturan-panel input[type="checkbox"] { margin-right: 10px; cursor: pointer; accent-color: #05c205; }
        .pengaturan-panel label { flex-grow: 1; cursor: pointer; }
        
        /* Legend Color */
        .legend-color { display: inline-block; width: 24px; height: 24px; border-radius: 4px; margin-left: 10px; border: 2px solid rgba(255,255,255,0.2); }
        .panel-close-btn { position: absolute; top: 10px; right: 15px; background: none; border: none; color: #aaa; font-size: 24px; cursor: pointer; }
        
        .leaflet-control-settings a {
            font-size: 1.4em; color: #333; width: 34px; height: 34px; line-height: 34px; text-align: center; background: #fff;
            border-radius: 4px; box-shadow: 0 1px 5px rgba(0,0,0,0.65); cursor: pointer;
        }
        .leaflet-top.leaflet-left { top: 80px; } 
        .leaflet-control-zoom a { width: 25px !important; height: 25px !important; line-height: 25px !important; font-size: 16px !important; }
        
        /* HEADER GRADASI ORANYE */
        #map-header-card {
            position: absolute; top: 20px; left: 20px; z-index: 1001;
            background: linear-gradient(to right, #f9a825, #fdd835);
            color: #333; padding: 8px 15px; border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
            font-weight: bold; font-size: 1.1em; text-transform: uppercase; pointer-events: none;
        }

        /* POPUP & MODAL */
        .leaflet-popup-content-wrapper { background: #fff; color: #333; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .custom-popup-title { padding: 12px 15px; font-weight: bold; font-size: 15px; border-bottom: 1px solid #f0f0f0; color: #05c205; }
        .custom-popup-button { background-color: #0d6efd; color: white; border: none; padding: 8px 20px; border-radius: 20px; width: 100%; cursor: pointer; font-size: 13px; font-weight: 600; }
        
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 2000; display: flex; justify-content: center; align-items: center; backdrop-filter: blur(4px); }
        .modal-content { background: #fff; width: 90%; max-width: 550px; border-radius: 15px; overflow: hidden; animation: slideUp 0.3s ease-out; display: flex; flex-direction: column; }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .modal-header { background: #05c205; color: white; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 0; max-height: 70vh; overflow-y: auto; }
        .detail-table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
        .detail-table th { text-align: left; padding: 12px 20px; width: 35%; color: #666; font-weight: 600; border-bottom: 1px solid #eee; }
        .detail-table td { padding: 12px 20px; color: #333; border-bottom: 1px solid #eee; }
        .divider-row td { background-color: #f0f0f0; height: 8px; padding: 0; border: none; }
        .modal-footer { padding: 15px 25px; text-align: right; border-top: 1px solid #eee; background: #fff; }
        .btn-tutup { background: #6c757d; color: white; border: none; padding: 8px 25px; border-radius: 50px; cursor: pointer; }
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
                        <input type="checkbox" class="kecamatan-checkbox" value="{{ $kecamatan->nama_kecamatan }}">
                        <label>{{ $kecamatan->nama_kecamatan }}</label>
                        <span class="legend-color" data-kecamatan="{{ $kecamatan->nama_kecamatan }}"></span>
                    </li>
                @endforeach
            @else <li>Data kecamatan tidak ditemukan.</li> @endif
        </ul>

        <h5>Batas Wilayah</h5>
        <ul id="kelurahan-filter-list">
            <li>
                <input type="checkbox" id="kelurahan-checkbox" checked>
                <label for="kelurahan-checkbox">Batas Kelurahan</label>
                <span class="legend-color" style="background-color: transparent; border: 2px dotted #999;"></span>
            </li>
        </ul>

        <h5>Sebaran Komplek</h5>
        <ul id="kompleks-filter-list">
            <li>
                <input type="checkbox" id="kompleks-checkbox" value="kompleks" checked>
                <label for="kompleks-checkbox">Sebaran Komplek</label>
                <span class="legend-color" style="background-color: #05c205; border: 2px solid white;"></span>
            </li>
        </ul>
    </div>

    <div id="map"></div>

    <div id="modal-detail" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Nama Komplek</h3>
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
        // 1. Inisialisasi Peta
        const map = L.map('map').setView([-3.32, 114.59], 13);
        
        // [PERBAIKAN UTAMA: PANES]
        // Ini membuat lapisan khusus agar Kelurahan selalu di atas Kecamatan
        map.createPane('paneKecamatan');
        map.getPane('paneKecamatan').style.zIndex = 399; // Layer Bawah
        
        map.createPane('paneKelurahan');
        map.getPane('paneKelurahan').style.zIndex = 450; // Layer Tengah (Di atas Kecamatan)
        
        map.createPane('paneKomplek');
        map.getPane('paneKomplek').style.zIndex = 600; // Layer Paling Atas

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap contributors' }).addTo(map);

        // 2. Variabel Global
        const kecamatanLayerGroups = {};
        const kompleksLayerGroup = L.layerGroup({pane: 'paneKomplek'}).addTo(map); 
        let allKelurahanLayer = null;
        let displayedKelurahanLayer = null;
        let kompleksDataStore = {};

        // Warna Spesifik
        const warnaKecamatanCustom = {
            "Banjarmasin Barat":   "#C2A68C", 
            "Banjarmasin Selatan": "#FDEB9E", 
            "Banjarmasin Tengah":  "#9A3F3F", 
            "Banjarmasin Timur":   "#6D94C5", 
            "Banjarmasin Utara":   "#FE7743"  
        };

        // Icon Rumah Hijau
        const GreenHouseIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color: white; border: 2px solid #05c205; width: 30px; height: 30px; border-radius: 50%; display: flex; justify-content: center; align-items: center; box-shadow: 0 3px 5px rgba(0,0,0,0.3);"><i class="fa-solid fa-house" style="color: #05c205; font-size: 14px;"></i></div>`,
            iconSize: [30, 30], iconAnchor: [15, 15], popupAnchor: [0, -15]
        });

        // 3. Fetch KECAMATAN (Layer Bawah)
        fetch('/api/kecamatan')
            .then(response => response.json())
            .then(data => {
                if (data && data.features) {
                    data.features.forEach(feature => {
                        const namaKecamatan = feature.properties.nama;
                        const warnaFinal = warnaKecamatanCustom[namaKecamatan] || feature.properties.warna || '#ccc';
                        
                        const legendSpan = document.querySelector(`.legend-color[data-kecamatan="${namaKecamatan}"]`);
                        if(legendSpan) legendSpan.style.backgroundColor = warnaFinal;

                        const layerGroup = L.layerGroup();
                        L.geoJSON(feature, {
                            pane: 'paneKecamatan', // Masuk ke pane bawah
                            style: { color: "#fff", weight: 1.5, fillColor: warnaFinal, fillOpacity: 0.8 },
                            onEachFeature: (f, l) => {
                                l.bindPopup(`<div style="text-align:center;"><strong>Kecamatan</strong><br>${f.properties.nama}</div>`);
                                l.on('click', function() { handleKecamatanClick(f.properties.nama); });
                            }
                        }).addTo(layerGroup);
                        
                        kecamatanLayerGroups[namaKecamatan] = layerGroup;

                        const cb = document.querySelector(`.kecamatan-checkbox[value="${namaKecamatan}"]`);
                        if (cb && cb.checked) layerGroup.addTo(map);
                    });
                }
            });

        // 4. Fetch KELURAHAN (Layer Tengah - Pasti Muncul)
        fetch('/api/kelurahan')
            .then(response => response.json())
            .then(data => {
                if (data && data.geojson) {
                    allKelurahanLayer = L.geoJSON(data.geojson, { 
                        pane: 'paneKelurahan', // Masuk ke pane tengah (di atas kecamatan)
                        style: { 
                            color: "#333",    // Garis warna gelap
                            weight: 1.5,      // Ketebalan garis
                            fillColor: "#fff", 
                            fillOpacity: 0,   // Transparan
                            dashArray: '5, 5' // Garis putus-putus
                        },
                        onEachFeature: (f, l) => {
                             const p = f.properties;
                             // Popup Lengkap
                             const popupContent = `
                                 <div style="font-family: sans-serif; font-size: 13px; min-width: 240px;">
                                     <strong style="color:#05c205; font-size:14px;">INFO WILAYAH</strong>
                                     <hr style="margin:5px 0; border:0; border-top:1px solid #eee;">
                                     <table style="width:100%; border-collapse:collapse;">
                                         <tr><td style="color:#666; width:80px;">Kota</td><td>: <strong>Banjarmasin</strong></td></tr>
                                         <tr><td style="color:#666;">Kecamatan</td><td>: ${p.nama_kecamatan || '-'}</td></tr>
                                         <tr><td style="color:#666;">Kelurahan</td><td>: ${p.nama_kelurahan || '-'}</td></tr>
                                         <tr><td style="color:#666;">Sumber</td><td>: ${p.sumber || '-'}</td></tr>
                                     </table>
                                 </div>
                             `;
                             l.bindPopup(popupContent);
                             
                             // Hover Effect
                             l.on('mouseover', function (e) {
                                 var layer = e.target;
                                 layer.setStyle({ weight: 3, color: '#05c205', dashArray: '', fillOpacity: 0.1 });
                             });
                             l.on('mouseout', function (e) {
                                 allKelurahanLayer.resetStyle(e.target);
                             });
                        }
                    });

                    // Jika checkbox dicentang, tambahkan ke peta
                    if (document.getElementById('kelurahan-checkbox').checked) {
                        allKelurahanLayer.addTo(map);
                    }
                }
            });

        // 5. Fetch KOMPLEKS (Layer Atas)
        fetch('/api/kompleks')
            .then(response => response.json())
            .then(data => {
                if (data && data.features) {
                    L.geoJSON(data, {
                        pane: 'paneKomplek', // Paling atas
                        pointToLayer: (feature, latlng) => { return L.marker(latlng, { icon: GreenHouseIcon }); },
                        onEachFeature: function(feature, layer) {
                            const props = feature.properties;
                            kompleksDataStore[props.id] = props;
                            if (props) {
                                const popupContent = `
                                <div style="font-family: sans-serif; min-width: 220px; text-align: center;">
                                    <h4 style="margin:0 0 5px 0; color:#05c205; font-size:14px;">${props.nama_perumahan}</h4>
                                    <div style="font-size:11px; color:#666; margin-bottom:10px;">${props.nama_pengembang}</div>
                                    <button onclick="lihatDetail(${props.id})" class="custom-popup-button">Lihat Detail</button>
                                </div>`;
                                layer.bindPopup(popupContent, { offset: [0, -15] }); 
                            }
                        }
                    }).addTo(kompleksLayerGroup);
                }
            });

        // 6. Logika Klik Kecamatan
        function handleKecamatanClick(namaKecamatanDiKlik) {
            if (displayedKelurahanLayer && map.hasLayer(displayedKelurahanLayer)) {
                map.removeLayer(displayedKelurahanLayer);
            }
            if (!allKelurahanLayer) return;

            const styleKelurahanBaru = { weight: 2, color: '#D32F2F', dashArray: '0', fillColor: '#fff', fillOpacity: 0.1 };
            displayedKelurahanLayer = L.layerGroup({pane: 'paneKelurahan'});
            let ditemukan = 0;

            allKelurahanLayer.eachLayer(function(layer) {
                let namaKelurahanKecamatan = layer.feature.properties.nama_kecamatan;
                if (namaKelurahanKecamatan && namaKecamatanDiKlik && 
                    namaKelurahanKecamatan.trim().toLowerCase() === namaKecamatanDiKlik.trim().toLowerCase()) {
                    ditemukan++;
                    let highlighted = L.geoJSON(layer.feature, { 
                         pane: 'paneKelurahan',
                         style: styleKelurahanBaru,
                         onEachFeature: (f, l) => l.bindPopup(layer.getPopup().getContent())
                    }); 
                    highlighted.addTo(displayedKelurahanLayer);
                }
            });
            if (ditemukan > 0) { displayedKelurahanLayer.addTo(map); }
        }
        
        map.on('click', function(e) {
             if (displayedKelurahanLayer && map.hasLayer(displayedKelurahanLayer)) {
                 if (!e.originalEvent.target.closest('.leaflet-popup-content-wrapper')) {
                     map.removeLayer(displayedKelurahanLayer); displayedKelurahanLayer = null;
                 }
             }
        });

        // 7. Panel Setting
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
        document.getElementById('kelurahan-checkbox').addEventListener('change', function(e) {
            if (allKelurahanLayer) {
                e.target.checked ? map.addLayer(allKelurahanLayer) : map.removeLayer(allKelurahanLayer);
            }
        });

        // Fungsi Modal
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