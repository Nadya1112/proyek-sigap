@extends('layouts.public')

@section('title', 'Syarat & Ketentuan - SIGAP KOMPLEK')

@section('content')
{{-- Wrapper untuk membuat konten di tengah dan memberi jarak dari footer --}}
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 md:py-20 px-6">
    <div class="max-w-4xl w-full bg-white p-8 md:p-12 rounded-2xl shadow-xl border border-gray-200/60">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 tracking-tight border-b pb-4">Syarat & Ketentuan</h1>

        <div class="mt-6 prose max-w-none prose-orange">
            <p>Selamat datang di SIGAP KOMPLEK. Dengan mendaftar atau menggunakan layanan kami, Anda setuju untuk terikat oleh syarat dan ketentuan berikut. Mohon baca dengan saksama.</p>
            
            <h2>1. Akun Pengguna</h2>
            <ul>
                <li>Anda bertanggung jawab penuh untuk menjaga kerahasiaan akun dan kata sandi Anda.</li>
                <li>Anda setuju untuk memberikan informasi yang akurat, terkini, dan lengkap saat proses pendaftaran.</li>
                <li>Setiap aktivitas yang terjadi di bawah akun Anda adalah tanggung jawab Anda.</li>
            </ul>

            <h2>2. Penggunaan Layanan</h2>
            <ul>
                <li>Layanan ini ditujukan untuk warga Kota Banjarmasin untuk mengajukan proposal dan pengaduan terkait Prasarana, Sarana, dan Utilitas Umum (PSU).</li>
                <li>Anda dilarang menggunakan layanan ini untuk tujuan yang melanggar hukum, menipu, atau merugikan pihak lain.</li>
                <li>Dilarang mengirimkan data yang tidak benar, bersifat spam, atau mengandung malware.</li>
            </ul>

            <h2>3. Konten Pengguna</h2>
            <p>Dengan mengirimkan pengaduan atau proposal, Anda memberikan kami hak untuk menggunakan, memproses, dan menampilkan informasi tersebut dalam rangka memberikan layanan.</p>

            <h2>4. Pembatasan Tanggung Jawab</h2>
            <p>Kami berusaha memberikan layanan terbaik, namun kami tidak menjamin bahwa layanan akan selalu bebas dari gangguan atau kesalahan. Kami tidak bertanggung jawab atas kerugian tidak langsung yang timbul dari penggunaan layanan ini.</p>

            <h2>5. Perubahan Ketentuan</h2>
            <p>Kami dapat mengubah syarat dan ketentuan ini dari waktu ke waktu. Perubahan akan diinformasikan melalui website. Dengan terus menggunakan layanan setelah perubahan, Anda dianggap menyetujui ketentuan yang baru.</p>

            <div class="mt-8 text-center">
                <a href="{{ url()->previous() }}" class="text-orange-600 font-semibold hover:underline">&larr; Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran - SIGAP KOMPLEK</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Warna Tema SIGAP */
            --sigap-orange: #F97316;
            --sigap-dark-orange: #ea580c;
            --sigap-gradient: linear-gradient(135deg, #F59E0B 0%, #F97316 100%);
            --text-dark: #333333;
            --text-grey: #666666;
            --border-color: #e5e7eb;
        }

        body { margin: 0; padding: 0; font-family: 'Poppins', sans-serif; background: #f4f4f4; overflow: hidden; }
        #map { height: 100vh; width: 100%; z-index: 1; }

        /* --- UI: TOMBOL & HEADER --- */
        .floating-btn {
            position: absolute; top: 24px; z-index: 1001;
            width: 44px; height: 44px; background: #fff; border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; justify-content: center; align-items: center;
            font-size: 18px; color: var(--sigap-orange); text-decoration: none; cursor: pointer; transition: 0.3s;
        }
        .floating-btn:hover { background: var(--sigap-orange); color: #fff; transform: translateY(-2px); }
        .btn-back { left: 24px; }
        .btn-layer { right: 24px; }

        #map-header-card {
            position: absolute; top: 24px; left: 80px; z-index: 1001;
            background: rgba(255, 255, 255, 0.95); padding: 0 20px; height: 44px;
            border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex; align-items: center; gap: 10px; backdrop-filter: blur(5px);
        }
        .header-logo { font-weight: 800; font-size: 15px; color: var(--text-dark); }
        .header-logo span { color: var(--sigap-orange); }
        .header-subtitle { font-size: 12px; color: var(--text-grey); padding-left: 10px; border-left: 1px solid #ddd; height: 20px; line-height: 20px; }

        /* --- UI: PANEL LAYER (DIPERBAIKI) --- */
        .pengaturan-panel {
            position: absolute; top: 80px; right: 24px; z-index: 1005;   
            width: 260px; background: #fff; border-radius: 12px; padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15); display: none; 
            animation: fadeIn 0.3s ease; max-height: 80vh; overflow-y: auto;
        }
        @keyframes fadeIn { from {opacity:0; transform:translateY(-10px);} to {opacity:1; transform:translateY(0);} }
        
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; }
        .panel-header h4 { margin: 0; font-size: 15px; font-weight: 700; color: var(--text-dark); }
        .close-panel { border: none; background: none; cursor: pointer; font-size: 18px; color: #999; }

        /* STYLE PEMISAH (SEPARATOR) BARU */
        .layer-separator {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8; /* Abu-abu seperti referensi */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 15px;
            margin-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 2px;
        }
        /* Hapus margin atas untuk item pertama */
        .layer-separator:first-of-type { margin-top: 5px; }

        .layer-item { display: flex; align-items: center; margin-bottom: 8px; font-size: 13px; color: #444; }
        .layer-item input { margin-right: 10px; accent-color: var(--sigap-orange); width: 16px; height: 16px; cursor: pointer; }
        .layer-item label { cursor: pointer; flex-grow: 1; font-weight: 500; }
        .legend-box { width: 14px; height: 14px; border-radius: 4px; margin-left: auto; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }

        /* --- UI: POPUP --- */
        .leaflet-popup-content-wrapper { border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); }
        .leaflet-popup-content { margin: 15px 20px; line-height: 1.6; min-width: 280px; font-family: 'Poppins', sans-serif; }
        .kelurahan-title { font-size: 16px; font-weight: 700; color: #40513B; margin-bottom: 10px; padding-bottom: 5px; }
        .komplek-popup-title { font-size: 15px; font-weight: 700; color: var(--sigap-orange); margin-bottom: 5px; }
        
        .popup-row { font-size: 13px; color: #333; margin-bottom: 4px; display: flex; }
        .popup-label { font-weight: 700; width: 90px; color: #444; flex-shrink: 0; }
        .popup-val { color: #555; }
        
        .btn-detail-popup {
            background: var(--sigap-gradient); color: #fff; border: none; 
            width: 100%; padding: 8px; margin-top: 10px; border-radius: 6px; 
            font-size: 12px; font-weight: 600; cursor: pointer; text-align: center;
        }
        .btn-detail-popup:hover { opacity: 0.9; }

        /* --- UI: MODAL DETAIL --- */
        .modal-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0,0,0,0.6); z-index: 2000; display: none; 
            justify-content: center; align-items: center; backdrop-filter: blur(2px);
        }
        .modal-box { 
            background: #fff; width: 95%; max-width: 600px; 
            border-radius: 12px; overflow: hidden; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            display: flex; flex-direction: column; max-height: 90vh;
        }
        .modal-header { 
            background: var(--sigap-gradient); 
            padding: 15px 20px; color: #fff; 
            display: flex; justify-content: space-between; align-items: center; 
        }
        .modal-header h3 { margin: 0; font-size: 18px; font-weight: 600; }
        .btn-close-modal { background: none; border: none; color: #fff; font-size: 24px; cursor: pointer; opacity: 0.9; }
        .modal-body { padding: 0; overflow-y: auto; background: #fff; }
        
        .detail-row { display: flex; padding: 12px 20px; border-bottom: 1px solid #eee; font-size: 14px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { width: 40%; color: #666; font-weight: 600; }
        .detail-value { width: 60%; color: #333; font-weight: 500; }
        
        .section-fasilitas { padding: 20px 20px 5px 20px; margin-top: 5px; }
        .text-fasilitas {
            color: var(--sigap-dark-orange); font-weight: 700; font-size: 13px; text-transform: uppercase;
            border-bottom: 2px solid var(--sigap-dark-orange); display: inline-block; padding-bottom: 2px; margin-bottom: 10px;
        }
        .modal-footer { padding: 15px 20px; border-top: 1px solid #eee; background: #f9f9f9; text-align: right; }
        .btn-grey-close {
            background: #6c757d; color: #fff; border: none; padding: 10px 30px; 
            border-radius: 50px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.2s;
        }
        .btn-grey-close:hover { background: #5a6268; }

        /* Marker */
        .icon-rumah-wrapper {
            background: #fff; border: 2px solid var(--sigap-orange);
            border-radius: 50%; width: 24px; height: 24px;
            display: flex; justify-content: center; align-items: center;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2); transition: transform 0.2s;
        }
        .icon-rumah-wrapper:hover { transform: scale(1.3); background: var(--sigap-orange); border-color: #fff; z-index: 999; }
        .icon-rumah-wrapper:hover i { color: #fff; }
        .icon-rumah-wrapper i { color: var(--sigap-orange); font-size: 12px; }

    </style>
</head>
<body>

    <a href="{{ url('/') }}" class="floating-btn btn-back" title="Kembali"><i class="fa-solid fa-arrow-left"></i></a>
    
    <div id="map-header-card">
        <div class="header-logo">SIGAP <span>KOMPLEK</span></div>
        <div class="header-subtitle">Peta Sebaran</div>
    </div>

    <div class="floating-btn btn-layer" onclick="togglePanel()"><i class="fa-solid fa-layer-group"></i></div>

    <div class="pengaturan-panel" id="panel-layer">
        <div class="panel-header"><h4>Filter Peta</h4><button class="close-panel" onclick="togglePanel()">&times;</button></div>
        
        <div class="layer-separator">Kecamatan</div>
        <div id="list-kecamatan">
            </div>

        <div class="layer-separator">Sebaran Perumahan</div>
        <div class="layer-item">
            <input type="checkbox" id="check-komplek" checked>
            <label for="check-komplek">Titik Perumahan</label>
            <div class="legend-box" style="background:var(--sigap-orange)"></div>
        </div>

        <div class="layer-separator">Lainnya</div>
        <div class="layer-item">
            <input type="checkbox" id="check-kelurahan" checked>
            <label for="check-kelurahan">Batas Kelurahan</label>
            <div class="legend-box" style="border:1px dashed #3B82F6"></div>
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
                <div class="detail-row"><div class="detail-label">Alamat</div><div class="detail-value" id="m-alamat">-</div></div>
                <div class="detail-row"><div class="detail-label">Kelurahan</div><div class="detail-value" id="m-kelurahan">-</div></div>
                <div class="detail-row"><div class="detail-label">Sertifikat</div><div class="detail-value" id="m-sertifikat">-</div></div>
                <div class="detail-row"><div class="detail-label">Unit</div><div class="detail-value" id="m-unit">-</div></div>
                <div class="detail-row"><div class="detail-label">Status Aset</div><div class="detail-value" id="m-status">-</div></div>
                <div class="section-fasilitas"><span class="text-fasilitas">FASILITAS</span></div>
                <div class="detail-row"><div class="detail-label">Ibadah</div><div class="detail-value" id="m-ibadah">-</div></div>
                <div class="detail-row"><div class="detail-label">Umum</div><div class="detail-value" id="m-umum">-</div></div>
                <div class="detail-row"><div class="detail-label">Pendidikan</div><div class="detail-value" id="m-pendidikan">-</div></div>
                <div class="detail-row"><div class="detail-label">Kesehatan</div><div class="detail-value" id="m-kesehatan">-</div></div>
            </div>
            <div class="modal-footer"><button class="btn-grey-close" onclick="closeModal()">Tutup</button></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map', { zoomControl: false }).setView([-3.32, 114.59], 13);
        L.control.zoom({ position: 'topleft' }).addTo(map);
        
        map.createPane('paneKecamatan'); map.getPane('paneKecamatan').style.zIndex = 390; 
        map.createPane('paneKelurahan'); map.getPane('paneKelurahan').style.zIndex = 450; 
        map.createPane('paneKomplek');   map.getPane('paneKomplek').style.zIndex = 650; 

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'SIGAP', maxZoom: 19 }).addTo(map);

        const layers = { komplek: L.layerGroup({pane: 'paneKomplek'}).addTo(map), kecamatan: {}, kelurahan: L.layerGroup({pane: 'paneKelurahan'}).addTo(map) };
        const dataStore = {}; 
        const colors = { "Banjarmasin Barat": "#F59E0B", "Banjarmasin Selatan": "#10B981", "Banjarmasin Tengah": "#EF4444", "Banjarmasin Timur": "#3B82F6", "Banjarmasin Utara": "#8B5CF6" };

        const iconRumah = L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="icon-rumah-wrapper"><i class="fa-solid fa-house"></i></div>`,
            iconSize: [24, 24], iconAnchor: [12, 12], popupAnchor: [0, -14] 
        });

        // 1. Fetch Kompleks
        fetch('{{ url("/api/kompleks") }}').then(r=>r.json()).then(d => {
            if(d.features) {
                L.geoJSON(d, {
                    pane: 'paneKomplek', 
                    pointToLayer: (f, latlng) => L.marker(latlng, { icon: iconRumah }),
                    onEachFeature: (f, l) => {
                        const p = f.properties;
                        dataStore[p.id] = p;
                        const popupContent = `
                            <div class="komplek-popup-title">${p.nama_perumahan}</div>
                            <div style="font-size:12px; color:#666;">
                                <strong>Pengembang:</strong><br>
                                ${(p.nama_pengembang || '-').replace(/;/g, '.')}
                            </div>
                            <button class="btn-detail-popup" onclick="openDetail(${p.id})">Lihat Detail</button>
                        `;
                        l.bindPopup(popupContent, { maxWidth: 280 });
                    }
                }).addTo(layers.komplek);
            }
        });

        // 2. Fetch Kelurahan
        fetch('{{ url("/api/kelurahan") }}').then(r=>r.json()).then(d => {
            if(d.geojson && d.geojson.features) {
                L.geoJSON(d.geojson, {
                    pane: 'paneKelurahan',
                    style: { color: '#3B82F6', weight: 1.5, dashArray: '5, 5', fillOpacity: 0.05, fillColor: '#3B82F6' },
                    onEachFeature: (f, l) => {
                        const props = f.properties;
                        const content = `
                            <div class="kelurahan-title">Kelurahan: ${props.nama_kelurahan}</div>
                            <div class="popup-row"><div class="popup-label">Kota:</div><div class="popup-val">Banjarmasin</div></div>
                            <div class="popup-row"><div class="popup-label">Kecamatan:</div><div class="popup-val">${props.nama_kecamatan || '-'}</div></div>
                            <div class="popup-row"><div class="popup-label">Sumber:</div><div class="popup-val">Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin</div></div>
                        `;
                        l.bindPopup(content, { maxWidth: 320 });
                        l.on('mouseover', e => e.target.setStyle({ weight: 3, fillOpacity: 0.2 }));
                        l.on('mouseout', e => e.target.setStyle({ weight: 1.5, fillOpacity: 0.05 }));
                    }
                }).addTo(layers.kelurahan);
            }
        });

        // 3. Fetch Kecamatan
        fetch('{{ url("/api/kecamatan") }}').then(r=>r.json()).then(d => {
            const ul = document.getElementById('list-kecamatan');
            if(d.features) d.features.forEach(f => {
                const nama = f.properties.nama;
                const warna = colors[nama] || '#999';
                layers.kecamatan[nama] = L.geoJSON(f, {
                    pane: 'paneKecamatan', style: { color: warna, weight: 1, fillColor: warna, fillOpacity: 0.1 }
                });
                const div = document.createElement('div');
                div.className = 'layer-item';
                div.innerHTML = `<input type="checkbox" value="${nama}" onchange="toggleKecamatan(this)"><label>${nama}</label><div class="legend-box" style="background:${warna}"></div>`;
                ul.appendChild(div);
            });
        });

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
            map.closePopup();

            document.getElementById('m-title').innerText = d.nama_perumahan;
            document.getElementById('m-alamat').innerText = (d.alamat || '-').replace(/;/g, '.');
            document.getElementById('m-kelurahan').innerText = (d.nama_kelurahan || '-').replace(/;/g, '.');
            document.getElementById('m-sertifikat').innerText = (d.jumlah_sertifikat != null ? d.jumlah_sertifikat : '-');
            document.getElementById('m-unit').innerText = (d.jumlah_unit != null ? d.jumlah_unit : '-');
            
            const status = d.status_aset || '-';
            let color = '#334155';
            if(status.includes('Sudah')) color = '#10B981';
            else if(status.includes('Belum')) color = '#EF4444';
            document.getElementById('m-status').innerHTML = `<span style="color:${color}; font-weight:700;">${status.replace(/;/g, '.')}</span>`;

            document.getElementById('m-ibadah').innerText = (d.fasilitas_ibadah || '-').replace(/;/g, '.');
            document.getElementById('m-umum').innerText = (d.fasilitas_umum || '-').replace(/;/g, '.');
            document.getElementById('m-pendidikan').innerText = (d.fasilitas_pendidikan || '-').replace(/;/g, '.');
            document.getElementById('m-kesehatan').innerText = (d.fasilitas_kesehatan || '-').replace(/;/g, '.');

            document.getElementById('modal-detail').style.display = 'flex';
        }
        window.closeModal = () => document.getElementById('modal-detail').style.display = 'none';
        document.getElementById('modal-detail').onclick = (e) => { if(e.target.id === 'modal-detail') closeModal(); };

    </script>
</body>
</html>