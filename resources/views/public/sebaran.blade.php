<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran Kompleks - SIGAP</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body { margin: 0; padding: 0; font-family: sans-serif; }
        #map { height: 100vh; width: 100%; }
        .map-header {
            position: absolute; top: 10px; left: 10px; z-index: 1000;
            background-color: #343a40; color: white; padding: 8px 15px;
            border-radius: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            display: flex; align-items: center;
        }
        .map-header .logo { margin-right: 12px; }
        .map-header .title { font-size: 1em; font-weight: 600; }
        
        .pengaturan-panel {
            position: absolute; top: 70px; right: 10px; z-index: 1000;
            background: white; padding: 15px; border-radius: 5px;
            width: 250px;
        }

        #kecamatan-filter-list { list-style: none; padding: 0; margin-top: 10px; }
        .legend-color { display: inline-block; width: 15px; height: 15px; border: 1px solid #555; vertical-align: middle; }
        .leaflet-top.leaflet-left { top: 70px; }
        .leaflet-control-zoom a {
            width: 26px; height: 26px; line-height: 26px; font-size: 18px;
        }

        #kecamatan-filter-list li {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        #kecamatan-filter-list li .item-label {
            flex-grow: 1; /* Membuat label nama memenuhi ruang kosong */
            margin-left: 8px; /* Jarak antara checkbox dan nama */
        }
        /* --- PERUBAHAN CSS --- */
        #kecamatan-filter-list li .legend-color {
            margin-left: auto; /* Mendorong kotak warna ke paling kanan */
        }
        /* -------------------- */

    </style>
</head>
<body>

    <div class="map-header">
        <svg width="28" height="28" viewBox="0 0 100 100" class="logo">
            <path d="M0 0 L50 0 L25 50 L50 100 L0 100 Z" fill="#007BFF"/>
            <path d="M50 0 L100 0 L75 50 L100 100 L50 100 Z" fill="#FFC107"/>
        </svg>
        <span class="title">SIGAP-KOMPLEK (WebGIS)</span>
    </div>

    <div class="pengaturan-panel">
        <h4>Pengaturan Layer</h4>
        <h5>Kecamatan</h5>
        <ul id="kecamatan-filter-list">
            @if(isset($kecamatans) && !$kecamatans->isEmpty())
                @foreach ($kecamatans as $kecamatan)
                    <li>
                        <input type="checkbox" class="kecamatan-checkbox" value="{{ $kecamatan->nama_kecamatan }}" checked>
                        <label class="item-label">{{ $kecamatan->nama_kecamatan }}</label>
                        <span class="legend-color" style="background-color: {{ $kecamatan->warna }};"></span>
                    </li>
                    @endforeach
            @else
                <li>Data kecamatan tidak ditemukan.</li>
            @endif
        </ul>
    </div>
    
    <div id="map"></div>

    <script>
        // ... Seluruh kode JavaScript Anda sudah benar dan tidak perlu diubah ...
        const map = L.map('map').setView([-3.32, 114.59], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const kecamatanLayerGroups = {};

        fetch('/api/kecamatan')
            .then(response => response.json())
            .then(data => {
                data.features.forEach(feature => {
                    const namaKecamatan = feature.properties.nama;
                    const layerGroup = L.layerGroup();
                    const geoJsonLayer = L.geoJSON(feature, {
                        style: {
                            color: "#000", weight: 1,
                            fillColor: feature.properties.warna, fillOpacity: 0.7
                        },
                        onEachFeature: (feature, layer) => {
                            layer.bindPopup(`<b>Kecamatan ${feature.properties.nama}</b>`);
                        }
                    });
                    geoJsonLayer.addTo(layerGroup);
                    kecamatanLayerGroups[namaKecamatan] = layerGroup;
                    layerGroup.addTo(map);
                });
            });

        fetch('/api/kompleks')
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data, {
                    style: { color: "#05c205", weight: 2, fillOpacity: 0.6 },
                    onEachFeature: function(feature, layer) {
                        const props = feature.properties;
                        if (props) {
                            layer.bindPopup(`<h4>${props.nama}</h4><p>${props.alamat || ''}</p>`);
                        }
                    }
                }).addTo(map);
            });

        document.querySelector('#kecamatan-filter-list').addEventListener('change', function(e) {
            if (e.target && e.target.matches('.kecamatan-checkbox')) {
                const checkbox = e.target;
                const namaKecamatan = checkbox.value;
                const layerGroup = kecamatanLayerGroups[namaKecamatan];

                if (layerGroup) {
                    if (checkbox.checked) {
                        map.addLayer(layerGroup);
                    } else {
                        map.removeLayer(layerGroup);
                    }
                }
            }
        });
    </script>
</body>
</html>