<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran Wilayah & Kompleks - SIGAP</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
        body { margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; background: #f4f4f4; }
        #map { height: 100vh; width: 100%; }

        /* --- 1. HEADER JUDUL (KIRI ATAS) --- */
        #map-header-card {
            position: absolute; 
            top: 20px; 
            left: 20px; 
            z-index: 1001;
            background: linear-gradient(90deg, #FFC107, #FF9800);
            color: #333; padding: 10px 20px; border-radius: 50px;
            font-weight: 800; box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            border: 2px solid #fff; font-size: 14px; letter-spacing: 0.5px;
            pointer-events: auto; 
        }

        /* --- 2. TOMBOL LAYER (KANAN ATAS) --- */
        .btn-layer-custom {
            position: absolute; 
            top: 20px; 
            right: 20px; 
            z-index: 1001;
            width: 40px; height: 40px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            display: flex; justify-content: center; align-items: center;
            font-size: 18px; color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid rgba(0,0,0,0.2);
        }
        .btn-layer-custom:hover { background: #f8f9fa; transform: scale(1.05); }

        /* --- 3. PANEL LAYER (KANAN ATAS - Di Bawah Tombol) --- */
        .pengaturan-panel {
            position: absolute; 
            top: 70px;       
            right: 20px;     
            z-index: 1005;   
            width: 250px; background: rgba(40, 40, 40, 0.95); 
            color: #fff; border-radius: 12px; padding: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5); display: none; 
            font-size: 12px; backdrop-filter: blur(8px); border: 1px solid #555;
        }
        .pengaturan-panel h4 { margin: 0 0 10px 0; border-bottom: 1px solid #666; padding-bottom: 8px; font-size: 13px; font-weight: 700; }
        .pengaturan-panel h5 { margin: 10px 0 5px 0; color: #aaa; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .pengaturan-panel ul { list-style: none; padding: 0; margin: 0; }
        .pengaturan-panel li { display: flex; align-items: center; margin-bottom: 6px; }
        .pengaturan-panel input[type="checkbox"] { margin-right: 8px; cursor: pointer; accent-color: #28a745; width: 14px; height: 14px; }
        .pengaturan-panel label { cursor: pointer; flex-grow: 1; }
        
        /* Kotak Warna Legenda (Untuk Kecamatan) */
        .legend-box { width: 16px; height: 16px; border-radius: 3px; margin-left: auto; border: 1px solid rgba(255,255,255,0.5); }
        
        /* Garis Legenda (Untuk Kelurahan) */
        .legend-line { width: 20px; height: 0; border-top: 2px dashed #999; margin-left: auto; margin-top: 8px; }

        .close-panel { position: absolute; top: 8px; right: 8px; background: none; border: none; color: #fff; cursor: pointer; font-size: 18px; opacity: 0.7; }
        .close-panel:hover { opacity: 1; }

        /* --- 4. ZOOM CONTROL (KIRI ATAS - DI BAWAH JUDUL) --- */
        /* Kita gunakan CSS ini untuk mendorong zoom control ke bawah agar tidak menabrak judul */
        .leaflet-top.leaflet-left {
            margin-top: 70px; 
            margin-left: 20px;
        }
        .leaflet-control-zoom {
            border: 2px solid rgba(0,0,0,0.2) !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3) !important;
            border-radius: 8px !important;
        }

        /* Style Ikon Rumah Hijau (KECIL - 20px) */
        .icon-rumah-wrapper {
            background: #fff; 
            border: 2px solid #28a745; 
            border-radius: 50%;
            width: 20px; height: 20px; 
            display: flex; justify-content: center; align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.4);
            transition: transform 0.2s;
        }
        .icon-rumah-wrapper:hover { transform: scale(1.5); z-index: 999; border-color: #218838; }
        .icon-rumah-wrapper i { color: #28a745; font-size: 10px; }

        /* Modal Detail */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 2000; display: none; justify-content: center; align-items: center; backdrop-filter: blur(3px); }
        .modal-box { background: #fff; width: 90%; max-width: 500px; border-radius: 12px; overflow: hidden; animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
        @keyframes slideUp { from {transform: translateY(50px); opacity: 0;} to {transform: translateY(0); opacity: 1;} }
        
        .modal-header { background: #28a745; color: #fff; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 20px; max-height: 60vh; overflow-y: auto; }
        .info-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .info-table th { text-align: left; color: #666; width: 35%; padding: 10px 5px; border-bottom: 1px solid #eee; font-weight: 600; vertical-align: top; }
        .info-table td { padding: 10px 5px; border-bottom: 1px solid #eee; color: #333; vertical-align: top; }
        .modal-footer { padding: 15px; text-align: right; background: #f9f9f9; border-top: 1px solid #eee; }
        .btn-tutup { background: #6c757d; color: white; border: none; padding: 8px 25px; border-radius: 50px; cursor: pointer; font-weight: 600; }
        .btn-detail { background: #007bff; color: white; border: none; padding: 6px 18px; border-radius: 20px; cursor: pointer; font-size: 12px; margin-top: 8px; width: 100%; font-weight: 600; }
        .btn-detail:hover { background: #0056b3; }
        .modal-close-btn { background:none; border:none; color:#fff; font-size:24px; cursor:pointer; line-height: 1; }
    </style>
</head>
<body>

    <!-- 1. Header Judul -->
    <div id="map-header-card">PETA - SEBARAN</div>

    <!-- 2. Tombol Layer Custom -->
    <div class="btn-layer-custom" onclick="togglePanel()" title="Pengaturan Layer">
        <i class="fa-solid fa-layer-group"></i>
    </div>

    <!-- 3. Panel Pengaturan -->
    <div class="pengaturan-panel" id="panel-layer">
        <button class="close-panel" onclick="togglePanel()">&times;</button>
        <h4>Pengaturan Layer</h4>
        
        <!-- SEBARAN KOMPLEK (Urutan Pertama & Checked) -->
        <h5>SEBARAN KOMPLEK</h5>
        <ul>
            <li>
                <input type="checkbox" id="check-komplek" checked>
                <label for="check-komplek">Sebaran Komplek</label>
                <div class="legend-box" style="background: #28a745; border: 2px solid white;"></div>
            </li>
        </ul>

        <!-- KECAMATAN (Urutan Kedua - UNCHECKED DEFAULT) -->
        <h5>KECAMATAN</h5>
        <ul id="list-kecamatan"></ul>

        <!-- BATAS WILAYAH (Urutan Ketiga) -->
        <h5>BATAS WILAYAH</h5>
        <ul>
            <li>
                <input type="checkbox" id="check-kelurahan" checked>
                <label for="check-kelurahan">Batas Kelurahan</label>
                <div class="legend-line"></div>
            </li>
        </ul>

        <div id="status-data" style="margin-top:10px; font-size:10px; color:#aaa; font-style:italic;">Memuat data...</div>
        <!-- Debug: Tombol bantuan untuk cek kelurahan -->
        <div style="margin-top:10px;">
            <button style="background:#495057;color:#fff;border:none;padding:6px 10px;border-radius:8px;cursor:pointer;font-size:12px;" onclick="showKelurahanDebug()">Debug Kelurahan</button>
        </div>
        <div id="debug-kelurahan-list" style="display:none;margin-top:10px;color:#ddd;font-size:12px;max-height:220px;overflow:auto;border-top:1px solid rgba(255,255,255,0.06);padding-top:8px;"></div>
    </div>

    <!-- 4. Container Peta -->
    <div id="map"></div>

    <!-- 5. Modal Detail -->
    <div id="modal-detail" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3 style="margin:0; font-size:18px;" id="m-title">Detail Komplek</h3>
                <button class="modal-close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <table class="info-table">
                    <tr><th>Alamat</th><td id="m-alamat">-</td></tr>
                    <tr><th>Kelurahan</th><td id="m-lokasi">-</td></tr>
                    <tr><th>Sertifikat</th><td id="m-sertifikat">-</td></tr>
                    <tr><th>Unit</th><td id="m-unit">-</td></tr>
                    <tr><th>Status Aset</th><td id="m-status">-</td></tr>
                    <tr><th colspan="2" style="padding-top:20px; color:#28a745; border-bottom: 2px solid #28a745;">FASILITAS</th></tr>
                    <tr><th>Ibadah</th><td id="m-ibadah">-</td></tr>
                    <tr><th>Umum</th><td id="m-umum">-</td></tr>
                    <tr><th>Pendidikan</th><td id="m-pendidikan">-</td></tr>
                    <tr><th>Kesehatan</th><td id="m-kesehatan">-</td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn-tutup" onclick="closeModal()">Tutup</button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // 1. Inisialisasi Peta
        const map = L.map('map', { zoomControl: false }).setView([-3.32, 114.59], 13);
        
        // Zoom Control (Top Left)
        L.control.zoom({ position: 'topleft' }).addTo(map);

        // -- KONFIGURASI PANE (Z-INDEX) --
        map.createPane('paneKecamatan'); map.getPane('paneKecamatan').style.zIndex = 390; 
        map.createPane('paneKelurahan'); map.getPane('paneKelurahan').style.zIndex = 450; 
        map.createPane('paneKomplek');   map.getPane('paneKomplek').style.zIndex = 650; 

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'SIGAP', maxZoom: 19 }).addTo(map);

        // Variabel Global
        const layers = {
            komplek: L.layerGroup({pane: 'paneKomplek'}).addTo(map), // Tampil
            kecamatan: {}, // Tidak ditampilkan default
            kelurahan: L.layerGroup({pane: 'paneKelurahan'}).addTo(map), // Tampil
            kelurahanGeoJSON: null // Untuk menyimpan geoJSON layer
        };
        const dataStore = {}; 
        const colors = { 
            "Banjarmasin Barat": "#8B6F47", "Banjarmasin Selatan": "#D4A574", 
            "Banjarmasin Tengah": "#C41E3A", "Banjarmasin Timur": "#1F4788", 
            "Banjarmasin Utara": "#FF6B35" 
        };

        // Ikon Rumah Hijau (Kecil)
        const iconRumah = L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="icon-rumah-wrapper"><i class="fa-solid fa-house"></i></div>`,
            iconSize: [20, 20], 
            iconAnchor: [10, 10], 
            popupAnchor: [0, -12] 
        });

        // --- FETCH DATA ---

        // 1. Fetch Kompleks
        fetch('{{ url("/api/kompleks") }}').then(r=>r.json()).then(d => {
            const count = d.features ? d.features.length : 0;
            document.getElementById('status-data').innerText = `Data dimuat: ${count} titik`;

            if(count > 0) {
                L.geoJSON(d, {
                    pane: 'paneKomplek', 
                    pointToLayer: (f, latlng) => L.marker(latlng, { icon: iconRumah }),
                    onEachFeature: (f, l) => {
                        const p = f.properties;
                        dataStore[p.id] = p;
                        l.bindPopup(`
                            <div style="text-align:center; font-family:sans-serif; min-width:200px;">
                                <h4 style="margin:0 0 8px 0; color:#28a745; font-size:15px;">${p.nama_perumahan}</h4>
                                <div style="font-size:12px; color:#555; margin-bottom:10px;">
                                    <strong>Pengembang</strong>: ${(p.nama_pengembang || '-').replace(/;/g, '.')}
                                </div>
                                <button class="btn-detail" onclick="openDetail(${p.id})">Lihat Detail</button>
                            </div>
                        `);
                    }
                }).addTo(layers.komplek);
            }
        }).catch(e => console.error("Error Kompleks:", e));

        // 2. Fetch Kecamatan (TANPA addTo(map) agar default HIDDEN)
        fetch('{{ url("/api/kecamatan") }}').then(r=>r.json()).then(d => {
            const ul = document.getElementById('list-kecamatan');
            if(d.features) d.features.forEach(f => {
                const nama = f.properties.nama;
                const warna = colors[nama] || '#ccc';
                // Create layer tanpa rectangle/polyline/focus
                const layer = L.geoJSON(f, {
                    pane: 'paneKecamatan', 
                    style: {
                        color: 'rgba(0,0,0,0.3)', // outline semi-transparan
                        weight: 2, // garis outline lebih tebal
                        fillColor: warna,
                        fillOpacity: 0.75 // lebih tebal/opaque
                    },
                    onEachFeature: (ft, ly) => {
                        ly.bindTooltip(nama, {permanent: false, direction: "center", className: "label-kecamatan"});
                        // Tidak ada kode fitBounds, rectangle, polyline, atau outline tambahan
                    }
                });
                layers.kecamatan[nama] = layer;
                const li = document.createElement('li');
                li.innerHTML = `<input type="checkbox" value="${nama}" onchange="toggleKecamatan(this)"><label>${nama}</label><div class="legend-box" style="background:${warna}"></div>`;
                ul.appendChild(li);
            });
        });

        // 3. Fetch Kelurahan (Visible - SEMUA DITAMPILKAN)
        fetch('{{ url("/api/kelurahan") }}').then(r=>r.json()).then(d => {
            console.log('Kelurahan Response:', d);
            const totalKelurahan = d.total_data || 0;
            const totalFeatures = d.total_features || (d.geojson?.features?.length || 0);
            console.log(`Kelurahan: ${totalFeatures} dari ${totalKelurahan} data`);
            
            if(d.geojson && d.geojson.features && d.geojson.features.length > 0) {
                layers.kelurahanGeoJSON = L.geoJSON(d.geojson, {
                    pane: 'paneKelurahan',
                    style: (feature) => {
                        // Warna army (hijau gelap)
                        const armyColor = '#4B5320';
                        return {
                            color: armyColor,
                            weight: 2,
                            dashArray: '5, 5',
                            fillOpacity: 0.15,
                            fillColor: armyColor
                        };
                    },
                    onEachFeature: (f, l) => {
                        const props = f.properties;
                        const popupContent = `
                            <div style="text-align:left; font-family:sans-serif; min-width:220px;">
                                <h4 style="margin:0 0 8px 0; color:#4B5320; font-size:15px;">Kelurahan: ${props.nama_kelurahan}</h4>
                                <div style="font-size:13px; color:#555; margin-bottom:10px;">
                                    <strong>Kota:</strong> Banjarmasin<br>
                                    <strong>Kecamatan:</strong> ${props.nama_kecamatan || '-'}<br>
                                    <strong>Sumber:</strong> ${props.sumber || '-'}
                                </div>
                            </div>
                        `;
                        l.bindPopup(popupContent, { maxWidth: 250 });
                        l.on('mouseover', e => {
                            e.target.setStyle({ weight: 3, dashArray: 'none', fillOpacity: 0.3 });
                        });
                        l.on('mouseout', e => {
                            layers.kelurahanGeoJSON.resetStyle(e.target);
                        });
                    }
                }).addTo(layers.kelurahan);
                // Populate debug list and add helper functions
                // ...hilangkan fitur debug console kelurahan...
                
                // Update status data
                document.getElementById('status-data').innerText = `Data dimuat: ${totalFeatures} kelurahan`;
            } else {
                console.warn('Tidak ada feature kelurahan yang ditemukan');
            }
        }).catch(e => console.error("Error Kelurahan:", e));

        // --- LOGIC UI ---

        function togglePanel() {
            const p = document.getElementById('panel-layer');
            p.style.display = (p.style.display === 'none' ? 'block' : 'none');
        }
        
        window.toggleKecamatan = (cb) => {
            const l = layers.kecamatan[cb.value];
            if(l) cb.checked ? map.addLayer(l) : map.removeLayer(l);
        }
        document.getElementById('check-kelurahan').onchange = (e) => e.target.checked ? map.addLayer(layers.kelurahan) : map.removeLayer(layers.kelurahan);
        document.getElementById('check-komplek').onchange = (e) => e.target.checked ? map.addLayer(layers.komplek) : map.removeLayer(layers.komplek);

        window.openDetail = (id) => {
            const d = dataStore[id];
            if(!d) return;
            document.getElementById('m-title').innerText = d.nama_perumahan;
            document.getElementById('m-alamat').innerText = (d.alamat || '-').replace(/;/g, '.');
            document.getElementById('m-lokasi').innerText = (d.kelurahan || '-').replace(/;/g, '.');
            document.getElementById('m-sertifikat').innerText = d.jumlah_sertifikat;
            document.getElementById('m-unit').innerText = d.jumlah_unit;
            document.getElementById('m-status').innerText = (d.status_aset || '-').replace(/;/g, '.');
            
            document.getElementById('m-ibadah').innerText = (d.fasilitas_ibadah || '-').replace(/;/g, '.');
            document.getElementById('m-umum').innerText = (d.fasilitas_umum || '-').replace(/;/g, '.');
            document.getElementById('m-pendidikan').innerText = (d.fasilitas_pendidikan || '-').replace(/;/g, '.');
            document.getElementById('m-kesehatan').innerText = (d.fasilitas_kesehatan || '-').replace(/;/g, '.');
            
            map.closePopup();
            document.getElementById('modal-detail').style.display = 'flex';
        }
        window.closeModal = () => document.getElementById('modal-detail').style.display = 'none';
        document.getElementById('modal-detail').onclick = (e) => { if(e.target.id === 'modal-detail') closeModal(); };

        // --- Debug helper functions (pop-in) ---
        window.showKelurahanDebug = () => {
            const container = document.getElementById('debug-kelurahan-list');
            if (!container) return;
            container.style.display = (container.style.display === 'none' ? 'block' : 'none');
        };

        window.listKelurahanToConsole = () => {
            if (!layers.kelurahanGeoJSON) return console.warn('kelurahanGeoJSON belum tersedia');
            const layersArr = layers.kelurahanGeoJSON.getLayers();
            console.log('Kelurahan layers count:', layersArr.length);
            layersArr.forEach((ly, i) => console.log(i, ly.feature?.properties));
        };

        window.highlightKelurahanRandomColors = () => {
            if (!layers.kelurahanGeoJSON) return;
            layers.kelurahanGeoJSON.getLayers().forEach((ly, i) => {
                try {
                    const color = '#'+(Math.floor(Math.random()*16777215).toString(16).padStart(6,'0'));
                    ly.setStyle({ color, fillColor: color, fillOpacity: 0.25, weight: 2 });
                } catch (e) {}
            });
        };

        window.fitKelurahanBounds = () => {
            if (!layers.kelurahanGeoJSON) return;
            try {
                const b = layers.kelurahanGeoJSON.getBounds();
                if (b.isValid()) map.fitBounds(b.pad(0.1));
            } catch (e) { console.warn('fitKelurahanBounds error', e); }
        };

    </script>
</body>
</html>