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
        /* ... CSS Anda (sama seperti sebelumnya) ... */
        body { margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
        #map { height: 100vh; width: 100%; }
        ul { list-style: none; padding: 0; margin: 0; }
        .pengaturan-panel {
            position: absolute; top: 80px; right: 20px; z-index: 1000;
            width: 280px; background: rgba(40, 40, 40, 0.9);
            color: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            padding: 15px; display: none;
        }
        .pengaturan-panel h4 { margin-top: 0; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #666; font-size: 1.1em; }
        .pengaturan-panel h5 { margin-top: 15px; margin-bottom: 10px; color: #ddd; font-size: 0.9em; text-transform: uppercase; }
        .pengaturan-panel li { display: flex; align-items: center; margin-bottom: 8px; font-size: 0.95em; }
        .pengaturan-panel input[type="checkbox"] { margin-right: 10px; }
        .pengaturan-panel label { flex-grow: 1; cursor: pointer; }
        .legend-color { display: inline-block; width: 20px; height: 20px; border: 1px solid #777; margin-left: 10px; }
        .panel-close-btn { position: absolute; top: 10px; right: 15px; background: none; border: none; color: #aaa; font-size: 24px; cursor: pointer; }
        .panel-close-btn:hover { color: #fff; }
        .leaflet-control-settings a {
            font-size: 1.4em; color: #333; width: 34px; height: 34px; line-height: 34px; text-align: center; background: #fff;
            border-radius: 4px; box-shadow: 0 1px 5px rgba(0,0,0,0.65); cursor: pointer;
        }
        
        /* Posisi kontrol Leaflet (zoom, dll) di bawah card kustom */
        .leaflet-top.leaflet-left { top: 80px; } 
        
        /* === PERUBAHAN: MEMPERKECIL TOMBOL ZOOM === */
        .leaflet-control-zoom a { 
            width: 25px !important;       /* Diperkecil */
            height: 25px !important;      /* Diperkecil */
            line-height: 25px !important; /* Pusatkan ikon */
            font-size: 16px !important;   /* Ukuran ikon +/- */
        }
        
        /* === CSS BARU UNTUK CARD PETA - SEBARAN (GRADASI) === */
        #map-header-card {
            position: absolute;
            top: 20px; 
            left: 20px; 
            z-index: 1001; /* Tampilkan di atas peta */
            
            /* Gradasi Oranye ke Kuning */
            background: linear-gradient(to right, #f9a825, #fdd835); /* Gradasi oranye/kuning */
            
            color: #333; /* Warna teks gelap agar kontras */
            padding: 8px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
            font-weight: bold;
            font-size: 1.1em;
            text-transform: uppercase;
            pointer-events: none; /* Agar klik bisa tembus ke peta di bawahnya */
        }

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
        .custom-popup-last-div { padding: 10px 15px; }
    </style>
</head>
<body>

    <div id="map-header-card">
        PETA - SEBARAN
    </div>

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

        // 2. Siapkan Wadah Layer
        const kecamatanLayerGroups = {};
        const kompleksLayerGroup = L.layerGroup();
        let allKelurahanLayer = null;
        let displayedKelurahanLayer = null;

        // ==========================================================
        // ===            [DEFINISI ICON KOMPLEKS]                  ===
        // ==========================================================
        const KompleksIcon = L.divIcon({
            className: 'custom-kompleks-icon',
            html: '<i class="fa-solid fa-house" style="color: #05c205; font-size: 18px;"></i>',
            iconSize: [25, 25],
            iconAnchor: [12, 18], 
            popupAnchor: [0, -18]
        });
        // ==========================================================


        // 3. Muat Data KECAMATAN
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


        // 4. Muat Data KELURAHAN
        fetch('/api/kelurahan')
            .then(response => response.json())
            .then(data => {
                if (data && data.geojson && data.geojson.features) {
                        allKelurahanLayer = L.geoJSON(data.geojson, { 
                            style: {
                                color: "#666",
                                weight: 1,
                                fillColor: "#ccc",
                                fillOpacity: 0.1
                            },
                            onEachFeature: (feature, layer) => {
                                 const kelProps = feature.properties;
                                 const kelPopupContent = `
                                 <div style="font-family: sans-serif; min-width: 300px;">
                                     <div class="custom-popup-title">Layer Properties</div>
                                     <div class="custom-popup-info"><strong>kota:</strong> BANJARMASIN</div>
                                     <div class="custom-popup-info"><strong>kecamatan:</strong> ${kelProps.nama_kecamatan || 'N/A'}</div>
                                     <div class="custom-popup-info"><strong>kelurahan:</strong> ${kelProps.nama_kelurahan || 'N/A'}</div>
                                     <div class="custom-popup-info" style="line-height: 1.4;"><strong>sumber:</strong> ${kelProps.sumber || 'N/A'}</div>
                                     <div class="custom-popup-button-div">
                                          <button onclick="map.closePopup(); lihatDetailKelurahan(${kelProps.id})" class="custom-popup-button">Lihat Detail</button>
                                     </div>
                                 </div>
                                 `;
                                 layer.bindPopup(kelPopupContent);
                            }
                        });
                        console.log("Data Kelurahan dari database berhasil dimuat.");
                } else { 
                        console.error("Data kelurahan dari API tidak valid..."); 
                }
            })
            .catch(error => console.error('Error fetching data kelurahan:', error));


        // 5. Muat Data KOMPLEKS (Diperbarui menggunakan L.marker dengan Ikon Kustom)
        fetch('/api/kompleks')
            .then(response => response.json())
            .then(data => {
                if (data && data.features) {
                    L.geoJSON(data, {
                        pointToLayer: (feature, latlng) => {
                            // Gunakan L.marker dengan Ikon Kustom
                            return L.marker(latlng, { icon: KompleksIcon }); 
                        },
                        onEachFeature: function(feature, layer) {
                            const props = feature.properties;
                            if (props) {
                                // === PERBAIKAN POPUP DISINI (Nama Komplek dan Nama Pengembang '-') ===
                                const popupContent = `<div style="font-family: sans-serif; min-width: 280px; padding: 0;">
                                    <div class="custom-popup-title">Layer Properties</div>
                                    <div class="custom-popup-info"><strong>Nama Komplek:</strong> ${props.nama_perumahan || 'Tidak Diketahui'}</div>
                                    <div class="custom-popup-info"><strong>Pengembang:</strong> -</div>
                                    <div class="custom-popup-button-div"><button onclick="map.closePopup(); lihatDetail(${props.id})" class="custom-popup-button">Lihat Detail</button></div>
                                </div>`;
                                // === BATAS PERBAIKAN POPUP ===
                                layer.bindPopup(popupContent, { offset: [0, -18] }); 
                            }
                        }
                    }).addTo(kompleksLayerGroup);
                    console.log("Data Kompleks berhasil dimuat ke layer group.");
                    
                    if (!document.getElementById('kompleks-checkbox').checked) {
                        if (map.hasLayer(kompleksLayerGroup)) {
                            map.removeLayer(kompleksLayerGroup);
                        }
                    }
                } else { console.error("Data kompleks tidak valid:", data); }
            })
            .catch(error => console.error('Error fetching data kompleks:', error));

        
        // ==========================================================
        // ===       FUNGSI HANDLEKLIK UNTUK KECAMATAN (START)      ===
        // ==========================================================
        function handleKecamatanClick(namaKecamatanDiKlik, layerKecamatan) {
            
            // Hapus kelurahan lama jika ada
            if (displayedKelurahanLayer && map.hasLayer(displayedKelurahanLayer)) {
                map.removeLayer(displayedKelurahanLayer);
            }
            
            if (!allKelurahanLayer) {
                alert("Data kelurahan belum siap. Cek Console F12.");
                return;
            }

            // --- STYLE HIGHLIGHT KELURAHAN (WARNA PASTEL) ---
            const styleKelurahanBaru = {
                weight: 2,
                color: '#F98888',     // Pastel Salmon (Garis Batas)
                dashArray: '5',
                fillColor: '#FFFFAA', // Pastel Kuning Lemon (Isi Poligon)
                fillOpacity: 0.7      // Tingkatkan opacity agar lebih jelas
            };
            // --- BATAS STYLE HIGHLIGHT ---

            displayedKelurahanLayer = L.layerGroup();
            let ditemukan = 0;

            // Loop semua kelurahan dan saring berdasarkan kecamatan
            allKelurahanLayer.eachLayer(function(layer) {
                let namaKelurahanKecamatan = layer.feature.properties.nama_kecamatan;

                if (namaKelurahanKecamatan && namaKecamatanDiKlik) {
                    
                    let cleanNamaKelurahan = namaKelurahanKecamatan.trim().toLowerCase();
                    let cleanNamaDiklik = namaKecamatanDiKlik.trim().toLowerCase();

                    if (cleanNamaKelurahan === cleanNamaDiklik) {
                        ditemukan++;
                        let highlighted = L.geoJSON(layer.feature, { 
                             style: styleKelurahanBaru,
                             onEachFeature: function(feature, highlightedLayer) {
                                 highlightedLayer.bindPopup(layer.getPopup().getContent());
                             }
                        }); 
                        highlighted.addTo(displayedKelurahanLayer);
                    }
                }
            });

            // Tampilkan layer kelurahan ke peta
            if (ditemukan > 0) {
                displayedKelurahanLayer.addTo(map);
                displayedKelurahanLayer.bringToFront(); 

            } else {
                console.log("Tidak ada kelurahan yang cocok untuk nama '" + namaKecamatanDiKlik + "'");
            }
        }
        // ==========================================================
        // ===       FUNGSI HANDLEKLIK UNTUK KECAMATAN (END)        ===
        // ==========================================================


        // Sembunyikan kelurahan jika klik di luar fitur (Logic yang Diperbaiki)
        map.on('click', function(e){
             if (displayedKelurahanLayer && map.hasLayer(displayedKelurahanLayer)) {
                 
                 let clickedOnFeature = false;
                 
                 displayedKelurahanLayer.eachLayer(layerGroup => {
                     layerGroup.eachLayer(layer => {
                         if (layer.contains(e.latlng)) { 
                             clickedOnFeature = true;
                         }
                     });
                 });
                 
                 if (!clickedOnFeature && !e.originalEvent.target.closest('.leaflet-popup-content-wrapper')) { 
                     map.removeLayer(displayedKelurahanLayer); 
                     displayedKelurahanLayer = null; 
                 }
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
                          if (displayedKelurahanLayer && map.hasLayer(displayedKelurahanLayer)) { map.removeLayer(displayedKelurahanLayer); displayedKelurahanLayer = null; }
                      }
                  }
              }
        });

        // Event listener untuk checkbox kompleks
        document.getElementById('kompleks-checkbox').addEventListener('change', function(e) {
            if (e.target.checked) {
                map.addLayer(kompleksLayerGroup);
            } else {
                map.removeLayer(kompleksLayerGroup);
            }
        });
        
        // ==========================================================
        // ===            [FUNGSI lihatDetail]                      ===
        // ==========================================================
        // Fungsi ini dikosongkan agar tidak ada alert saat diklik.
        function lihatDetail(idKompleks) {
            console.log("Tombol Lihat Detail Kompleks ID:", idKompleks + " diklik.");
            // Tambahkan di sini logika untuk pindah ke halaman detail yang sebenarnya
        }
        
        function lihatDetailKelurahan(idKelurahan) {
            console.log("Tombol Lihat Detail Kelurahan ID:", idKelurahan + " diklik.");
            // Tombol di popup kelurahan sudah ditambahkan map.closePopup() di inline HTML
            // Tambahkan di sini logika untuk pindah ke halaman detail kelurahan
        }
        // ==========================================================

    </script>
</body> 
</html>