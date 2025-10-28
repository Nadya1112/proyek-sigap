<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran Kompleks - SIGAP</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
        body { margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
        #map { height: 100vh; width: 100%; }
        ul { list-style: none; padding: 0; margin: 0; }

        /* Panel Pengaturan (Gelap) */
        .pengaturan-panel {
            position: absolute; top: 80px; right: 20px; z-index: 1000;
            width: 280px; background: rgba(40, 40, 40, 0.9);
            color: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            padding: 15px; display: none; /* Sembunyi secara default */
        }
        .pengaturan-panel h4 { margin-top: 0; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #666; font-size: 1.1em; }
        .pengaturan-panel h5 { margin-top: 15px; margin-bottom: 10px; color: #ddd; font-size: 0.9em; text-transform: uppercase; }
        .pengaturan-panel li { display: flex; align-items: center; margin-bottom: 8px; font-size: 0.95em; }
        .pengaturan-panel input[type="checkbox"] { margin-right: 10px; }
        .pengaturan-panel label { flex-grow: 1; cursor: pointer; }
        .legend-color { display: inline-block; width: 20px; height: 20px; border: 1px solid #777; margin-left: 10px; }
        .panel-close-btn { position: absolute; top: 10px; right: 15px; background: none; border: none; color: #aaa; font-size: 24px; cursor: pointer; }
        .panel-close-btn:hover { color: #fff; }
        /* Tombol Pengaturan di Peta */
        .leaflet-control-settings a {
            font-size: 1.4em; color: #333; width: 34px; height: 34px; line-height: 34px; text-align: center; background: #fff;
            border-radius: 4px; box-shadow: 0 1px 5px rgba(0,0,0,0.65); cursor: pointer;
        }

        /* --- CSS UNTUK TOMBOL ZOOM --- */
        .leaflet-top.leaflet-left { top: 80px; }
        .leaflet-control-zoom a { width: 28px; height: 28px; line-height: 28px; font-size: 18px; }
        /* ----------------------------- */

        /* --- CSS UNTUK POPUP PUTIH --- */
        .leaflet-popup-content-wrapper { background: #fff; color: #333; border-radius: 8px; box-shadow: 0 1px 5px rgba(0,0,0,0.4); }
        .leaflet-popup-tip-container .leaflet-popup-tip { background: #fff; }
        .leaflet-popup-content { margin: 0 !important; padding: 0; width: auto !important; font-size: 14px; line-height: 1.6; }
        .custom-popup-title { padding: 10px 15px; font-weight: bold; font-size: 15px; border-bottom: 1px solid #eee; position: relative; }
        .leaflet-popup-close-button { position: absolute; top: 5px; right: 10px; padding: 5px; border: none; background: none; font-size: 20px; color: #888; }
        .leaflet-popup-close-button:hover { color: #333; background: none; }
        .custom-popup-info { padding: 10px 15px; border-bottom: 1px solid #eee; }
        .custom-popup-info strong { margin-right: 5px; }
        .custom-popup-button-div { padding: 15px; }
        .custom-popup-button { background-color: #0d6efd; color: white; border: none; padding: 10px 15px; border-radius: 5px; width: 100%; cursor: pointer; font-size: 14px; text-align: center; }
        .custom-popup-last-div { padding: 10px 15px; } /* Div terakhir popup kelurahan */
        /* ----------------------------- */

    </style>
</head>
<body>

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
                        <span class="legend-color" style="background-color: {{ $kecamatan->warna }};"></span>
                    </li>
                @endforeach
            @else <li>Data kecamatan tidak ditemukan.</li> @endif
        </ul>

        <h5>Sebaran Komplek</h5>
        <ul id="kompleks-filter-list">
            <li>
                <input type="checkbox" id="kompleks-checkbox" value="kompleks">
                <label for="kompleks-checkbox">Sebaran Komplek</label>
                <span class="legend-color" style="background-color: #05c205;"></span>
            </li>
        </ul>
    </div>

    <div id="map"></div>

    <script>
        // 1. Inisialisasi Peta
        const map = L.map('map').setView([-3.32, 114.59], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors' }).addTo(map);

        // 2. Siapkan Wadah Layer & Style
        const kecamatanLayerGroups = {};
        const kompleksLayerGroup = L.layerGroup();
        let allKelurahanLayer = null;
        let displayedKelurahanLayer = null;
        const kelurahanStyle = { weight: 2, color: '#E6DB6A', dashArray: '', fillColor: '#E6DB6A', fillOpacity: 0.6 };

        // 3. Muat Data KECAMATAN (API + Popup Kustom + Klik)
        fetch('/api/kecamatan')
            .then(response => response.json())
            .then(data => {
                if (data && data.features) {
                    data.features.forEach(feature => {
                        const namaKecamatan = feature.properties.nama;
                        const layerGroup = L.layerGroup();
                        L.geoJSON(feature, {
                            style: { color: "#000", weight: 1, fillColor: feature.properties.warna, fillOpacity: 0.9 },
                            onEachFeature: (feature, layer) => {
                                const kecProps = feature.properties;
                                const kecPopupContent = `<div style="font-family: sans-serif; min-width: 250px;"><div class="custom-popup-title">Layer Properties</div><div class="custom-popup-info"><strong>Kecamatan:</strong> ${kecProps.nama || 'Tidak Diketahui'}</div></div>`;
                                layer.bindPopup(kecPopupContent);
                                layer.on('click', function(e) { handleKecamatanClick(kecProps.nama, layer); });
                            }
                        }).addTo(layerGroup);
                        kecamatanLayerGroups[namaKecamatan] = layerGroup;
                    });
                } else { console.error("Data kecamatan API tidak valid:", data); }
            })
            .catch(error => console.error('Error fetching data kecamatan:', error));

        // 4. Muat Data KELURAHAN (API + Popup Kustom)
        fetch('/api/kelurahan')
             .then(response => response.json())
             .then(data => {
                 if (data && data.features) {
                     allKelurahanLayer = L.geoJSON(data, {
                         onEachFeature: (feature, layer) => {
                             const kelProps = feature.properties;
                             const kelPopupContent = `<div style="font-family: sans-serif; min-width: 250px;"><div class="custom-popup-title">Layer Properties</div><div class="custom-popup-info"><strong>Kelurahan:</strong> ${kelProps.nama_kel || 'Tidak Diketahui'}</div><div class="custom-popup-last-div"><strong>Kecamatan:</strong> ${kelProps.nama_kec || 'Tidak Diketahui'}</div></div>`;
                             layer.bindPopup(kelPopupContent);
                         }
                     });
                     console.log("Data Kelurahan dari database berhasil dimuat.");
                 } else { console.error("Data kelurahan dari API tidak valid."); }
             })
             .catch(error => console.error('Error fetching data kelurahan:', error));

        // 5. Muat Data KOMPLEKS (API + Popup Kustom)
        fetch('/api/kompleks')
            .then(response => response.json())
            .then(data => {
                if (data && data.features) {
                    L.geoJSON(data, {
                        pointToLayer: (feature, latlng) => L.marker(latlng),
                        onEachFeature: function(feature, layer) { // <-- POPUP KUSTOM PUTIH UNTUK KOMPLEKS
                            const props = feature.properties;
                            if (props) {
                                const popupContent = `<div style="font-family: sans-serif; min-width: 280px;"><div class="custom-popup-title">Layer Properties</div><div class="custom-popup-info"><strong>Nama Perumahan:</strong> ${props.nama_perumahan || 'Tidak Diketahui'}</div><div class="custom-popup-info"><strong>Nama Pengembang:</strong> ${props.nama_pengembang || 'Tidak Diketahui'}</div><div class="custom-popup-button-div"><button onclick="lihatDetail(${props.id})" class="custom-popup-button">Lihat Detail</button></div></div>`;
                                layer.bindPopup(popupContent, { offset: [0, -10] });
                            }
                        }
                    }).addTo(kompleksLayerGroup);
                } else { console.error("Data kompleks tidak valid:", data); }
            })
            .catch(error => console.error('Error fetching data kompleks:', error));

        // --- FUNGSI UNTUK MENANGANI KLIK KECAMATAN ---
        function handleKecamatanClick(namaKecamatanDiKlik, layerKecamatan) {
            if (displayedKelurahanLayer && map.hasLayer(displayedKelurahanLayer)) { map.removeLayer(displayedKelurahanLayer); }
            if (!allKelurahanLayer) { alert("Data kelurahan belum siap."); return; }

            displayedKelurahanLayer = L.layerGroup();
            allKelurahanLayer.eachLayer(function(layer) {
                if (layer.feature.properties.nama_kec === namaKecamatanDiKlik) {
                    let highlighted = L.geoJSON(layer.feature, { style: kelurahanStyle });
                    if (layer.getPopup()) highlighted.bindPopup(layer.getPopup().getContent());
                    highlighted.addTo(displayedKelurahanLayer);
                }
            });

            if (displayedKelurahanLayer.getLayers().length > 0) displayedKelurahanLayer.addTo(map);
            else { console.log("Tidak ada kelurahan untuk:", namaKecamatanDiKlik); displayedKelurahanLayer = null; }

            // (Opsional) Zoom ke kecamatan
            // if (layerKecamatan) map.fitBounds(layerKecamatan.getBounds());
        }
        // ----------------------------------------------

        // Sembunyikan kelurahan jika klik di luar fitur
         map.on('click', function(e){
              if (displayedKelurahanLayer && map.hasLayer(displayedKelurahanLayer)) {
                  let clickedOnFeature = false;
                  displayedKelurahanLayer.eachLayer(layer => { if (e.originalEvent.target === layer._path) clickedOnFeature = true; });
                  Object.values(kecamatanLayerGroups).forEach(group => { if(map.hasLayer(group)) { group.eachLayer(layer => { if (e.originalEvent.target === layer._path) clickedOnFeature = true; }); } });
                  if (!clickedOnFeature) { map.removeLayer(displayedKelurahanLayer); displayedKelurahanLayer = null; }
              }
         });

        // 6. Tombol Pengaturan & Fungsikan Panel
        L.Control.Settings = L.Control.extend({
            onAdd: (map) => {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-settings');
                container.innerHTML = `<a href="#" title="Pengaturan Layer" role="button"><i class="fa-solid fa-layer-group"></i></a>`;
                L.DomEvent.disableClickPropagation(container).on(container, 'click', () => { document.getElementById('pengaturan-panel').style.display = 'block'; });
                return container;
            }
        });
        new L.Control.Settings({ position: 'topright' }).addTo(map);

        const panel = document.getElementById('pengaturan-panel');
        document.getElementById('panel-close-btn').addEventListener('click', () => { panel.style.display = 'none'; });

        document.getElementById('kecamatan-filter-list').addEventListener('change', function(e) {
            if (e.target && e.target.matches('.kecamatan-checkbox')) {
                const cb = e.target, name = cb.value, group = kecamatanLayerGroups[name];
                if (group) {
                    if (cb.checked) map.addLayer(group);
                    else {
                         map.removeLayer(group);
                         // Sembunyikan kelurahan jika kecamatannya dimatikan
                         if (displayedKelurahanLayer && map.hasLayer(displayedKelurahanLayer)) { map.removeLayer(displayedKelurahanLayer); displayedKelurahanLayer = null; }
                    }
                }
            }
        });

        document.getElementById('kompleks-checkbox').addEventListener('change', function(e) {
            if (e.target.checked) map.addLayer(kompleksLayerGroup);
            else map.removeLayer(kompleksLayerGroup);
        });

        // 7. Fungsi untuk tombol "Lihat Detail"
        function lihatDetail(idKompleks) {
            console.log("Lihat detail Kompleks ID:", idKompleks);
            alert("Tombol Lihat Detail ID " + idKompleks + " diklik. Fitur belum jadi.");
            // window.location.href = '/detail-kompleks/' + idKompleks;
        }

    </script>
</body>
</html>