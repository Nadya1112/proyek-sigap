<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-g">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran Kompleks - SIGAP Komplek</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body { margin: 0; padding: 0; font-family: sans-serif; }
        #map {
            height: 100vh; /* Peta akan setinggi layar penuh */
            width: 100%;
        }
        .info-panel { /* Style untuk panel info di atas peta */
             position: absolute;
             top: 10px;
             left: 50px;
             z-index: 1000;
             background: white;
             padding: 10px;
             border-radius: 5px;
             border: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <div class="info-panel">
        <h1>Peta Sebaran Kompleks Perumahan</h1>
    </div>
    <div id="map"></div>


    <script>
        // Inisialisasi Peta, fokus ke Banjarmasin
        const map = L.map('map').setView([-3.32, 114.59], 13);

        // Tambahkan Tile Layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Ambil data GeoJSON dari API kita
        fetch('/api/kompleks')
            .then(response => response.json())
            .then(data => {
                // Tampilkan data Poligon di Peta
                L.geoJSON(data, {
                    style: function(feature) {
                        return {
                            color: "#026e02",
                            weight: 2,
                            opacity: 1,
                            fillColor: "#05c205",
                            fillOpacity: 0.6
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        const props = feature.properties;
                        if (props) {
                            layer.bindPopup(`<h4>${props.nama}</h4><p>${props.alamat || ''}</p>`);
                        }
                    }
                }).addTo(map);
            })
            .catch(error => console.error('Gagal mengambil data GeoJSON:', error));
    </script>

</body>
</html>