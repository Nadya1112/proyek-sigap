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
        .pengaturan-panel {
            position: absolute; top: 20px; right: 20px; z-index: 1000;
            width: 280px; background: rgba(40, 40, 40, 0.9);
            color: #ffffff; border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            padding: 15px; display: none;
        }
        .pengaturan-panel h4 {
            margin-top: 0; margin-bottom: 15px; padding-bottom: 10px;
            border-bottom: 1px solid #666; font-size: 1.1em;
        }
        .pengaturan-panel h5 {
            margin-top: 15px; margin-bottom: 10px; color: #ddd;
            font-size: 0.9em; text-transform: uppercase;
        }
        .pengaturan-panel li {
            display: flex; align-items: center; margin-bottom: 8px;
            font-size: 0.95em;
        }
        .pengaturan-panel input[type="checkbox"] { margin-right: 10px; }
        .pengaturan-panel label { flex-grow: 1; cursor: pointer; }
        .legend-color {
            display: inline-block; width: 20px; height: 20px;
            border: 1px solid #777; margin-left: 10px;
        }
        .panel-close-btn {
            position: absolute; top: 10px; right: 15px; background: none;
            border: none; color: #aaa; font-size: 24px; cursor: pointer;
        }
        .panel-close-btn:hover { color: #fff; }
        .leaflet-control-settings a {
            font-size: 1.4em; color: #333; width: 34px; height: 34px;
            line-height: 34px; text-align: center; background: #fff;
            border-radius: 4px; box-shadow: 0 1px 5px rgba(0,0,0,0.65);
            cursor: pointer;
        }
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
            @else
                <li>Data kecamatan tidak ditemukan.</li>
            @endif
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
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // 2. Siapkan Wadah (Layer Groups)
        const kecamatanLayerGroups = {};
        const kompleksLayerGroup = L.layerGroup(); // Layer mati (off) by default

        // 3. Ambil Data Poligon Kecamatan (dari API)
        fetch('/api/kecamatan')
            .then(response => response.json())
            .then(data => {
                if (data && data.features) {
                    data.features.forEach(feature => {
                        const namaKecamatan = feature.properties.nama;
                        const layerGroup = L.layerGroup();
                        L.geoJSON(feature, {
                            style: {
                                color: "#000", weight: 1,
                                fillColor: feature.properties.warna,
                                fillOpacity: 0.9 // Warna tebal
                            },
                            onEachFeature: (feature, layer) => {
                                layer.bindPopup(`<b>Kecamatan ${feature.properties.nama}</b>`);
                            }
                        }).addTo(layerGroup);
                        kecamatanLayerGroups[namaKecamatan] = layerGroup;
                        // Layer TIDAK ditambahkan ke peta secara default
                    });
                } else {
                    console.error("Data kecamatan tidak valid atau kosong:", data);
                }
            })
            .catch(error => console.error('Error fetching data kecamatan:', error));

        // 4. Ambil Data Titik Kompleks (dari API)
        fetch('/api/kompleks')
            .then(response => response.json())
            .then(data => {
                if (data && data.features) {
                    L.geoJSON(data, {
                        pointToLayer: function (feature, latlng) {
                           return L.marker(latlng);
                        },
                        style: { color: "#05c205", weight: 2, fillOpacity: 0.6 },
                        onEachFeature: function(feature, layer) {
                            const props = feature.properties;
                            if (props) {
                                layer.bindPopup(`<h4>${props.nama || 'Data Komplek'}</h4>
                                                 <p>Kel. ${props.kelurahan || ''}</p>
                                                 <p>Kec. ${props.kecamatan || ''}</p>`);
                            }
                        }
                    }).addTo(kompleksLayerGroup);
                } else {
                    console.error("Data kompleks tidak valid atau kosong:", data);
                }
            })
            .catch(error => console.error('Error fetching data kompleks:', error));

        // 5. Buat Tombol Pengaturan (Tidak ada perubahan)
        L.Control.Settings = L.Control.extend({
            onAdd: function(map) {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-settings');
                container.innerHTML = `<a href="#" title="Pengaturan Layer" role="button" aria-label="Pengaturan Layer"><i class="fa-solid fa-layer-group"></i></a>`;
                L.DomEvent.disableClickPropagation(container);
                L.DomEvent.on(container, 'click', function(e) {
                    document.getElementById('pengaturan-panel').style.display = 'block';
                });
                return container;
            }
        });
        new L.Control.Settings({ position: 'topright' }).addTo(map);

        // 6. Fungsikan Panel Pengaturan
        const panel = document.getElementById('pengaturan-panel');
        document.getElementById('panel-close-btn').addEventListener('click', function() {
            panel.style.display = 'none';
        });

        // -----------------------------------------------------------
        // LOGIKA KEMBALI KE CHECKBOX BIASA (BISA MULTIPLE)
        // -----------------------------------------------------------
        document.getElementById('kecamatan-filter-list').addEventListener('change', function(e) {
            // Pastikan yang diklik adalah checkbox kecamatan
            if (e.target && e.target.matches('.kecamatan-checkbox')) {

                const checkbox = e.target;
                const namaKecamatan = checkbox.value;
                const layerGroup = kecamatanLayerGroups[namaKecamatan];

                if (layerGroup) {
                    // Jika dicentang, tambahkan layer ke peta
                    if (checkbox.checked) {
                        map.addLayer(layerGroup);
                    }
                    // Jika centang dihilangkan, hapus layer dari peta
                    else {
                        map.removeLayer(layerGroup);
                    }
                }
            }
        });

        // Fungsikan Checkbox Sebaran Komplek (Tetap Independen)
        document.getElementById('kompleks-checkbox').addEventListener('change', function(e) {
            if (e.target.checked) {
                map.addLayer(kompleksLayerGroup);
            } else {
                map.removeLayer(kompleksLayerGroup);
            }
        });

    </script>
</body>
</html>