from datetime import date, timedelta
import os

# Define the start and end dates
start_date = date(2025, 8, 1)
end_date = date(2025, 12, 16)

# Single CP Mapping per Week (1-18)
# wk_cp_map keys must match the weekly_plan keys
wk_cp_map = {
    1: "KU3",   # Planning/Design -> Problem Solving
    2: "KU2",   # Implementation -> Quality Performance
    3: "S9",    # Finalization -> Responsibility
    4: "KU8",   # Testing/Auth -> Documentation/Data Security
    5: "KK14",  # Analysis/Design -> Technical Communication
    6: "KU2",   # Implementation -> Quality Performance
    7: "S9",    # Edit/Update -> Responsibility
    8: "KU8",   # Testing -> Data verification
    9: "KU3",   # Planning Admin -> Problem Solving
    10: "S9",   # CRUD Admin -> Responsibility
    11: "KU2",  # Inbox/Response -> Quality Performance
    12: "KU5",  # Integration -> Teamwork/Communication
    13: "KU8",  # Final Code/Security -> Data security
    14: "KU2",  # Blackbox -> Quality Performance
    15: "KK14", # Stats Plan -> Technical Communication
    16: "S9",   # Stats Impl -> Responsibility
    17: "KU4",  # Report -> Reporting
    18: "P10"   # Presentasi -> Oral Communication
}

weekly_plan = {
    1: { # Aug 4 - 8
        "theme": "Perencanaan fitur prioritas dan desain UI/UX Dashboard Admin & Landing Page.",
        "tasks": [
            ("Melakukan diskusi dengan pembimbing mengenai kebutuhan fitur utama sistem.", "Catatan Notulensi", "Foto Diskusi"),
            ("Membuat User Flow dan Sitemap untuk Landing Page dan Dashboard Admin.", "Dokumen User Flow", "Screenshot Sitemap"),
            ("Merancang Wireframe (Low-Fidelity) untuk antarmuka Landing Page.", "Desain Wireframe", "Screenshot Wireframe"),
            ("Mendesain UI (High-Fidelity) Dashboard Admin menggunakan Figma.", "Mockup Admin", "Screenshot Desain Figma"),
            ("Finalisasi desain UI Landing Page dan evaluasi branding.", "Mockup Landing Page", "Screenshot Desain Figma")
        ]
    },
    2: { # Aug 11 - 15
        "theme": "Implementasi Frontend Landing Page dan struktur Dashboard Admin.",
        "tasks": [
            ("Setup project environment (Laravel/Vite) dan instalasi dependency.", "Project Repository", "Screenshot VS Code/Terminal"),
            ("Slicing desain Landing Page dari Figma ke kode HTML/CSS.", "File Blade Landing Page", "Screenshot Kodingan"),
            ("Implementasi responsifitas (mobile-friendly) Landing Page.", "Tampilan Mobile", "Foto Tampilan di HP"),
            ("Membangun struktur layout utama (Master Layout) Dashboard Admin.", "Layout Blade", "Screenshot Halaman Admin"),
            ("Integrasi komponen UI dasar Dashboard Admin (Sidebar, Navbar).", "Komponen UI", "Screenshot Sidebar/Nav")
        ]
    },
    3: { # Aug 18 - 22
        "theme": "Finalisasi coding Frontend Landing Page dan Dashboard Admin.",
        "tasks": [
            ("Menambahkan interaksi dinamis pada Landing Page (JS/Alpine.js).", "Script JS interactive", "Screenshot Code JS"),
            ("Melengkapi halaman-halaman statis pada Dashboard Admin.", "Halaman Statis", "Screenshot Halaman"),
            ("Perbaikan tampilan (UI Bug Fixing) pada Landing Page.", "Revisi UI", "Screenshot Perbaikan"),
            ("Optimasi aset gambar dan script untuk performa.", "Aset Teroptimasi", "Screenshot Inspect Element"),
            ("Review kode frontend sebelum masuk tahap backend.", "Clean Code", "Screenshot Struktur Folder")
        ]
    },
    4: { # Aug 25 - 29
        "theme": "Pengujian (Testing) fitur login dan mekanisme redirect role user.",
        "tasks": [
            ("Membuat database schema tabel Users dan konfigurasi Auth.", "Schema Database", "Screenshot PHPMyAdmin/Migration"),
            ("Implementasi fitur Login dan Register pada backend.", "Fitur Auth Berjalan", "Screenshot Halaman Login"),
            ("Membuat Middleware pembeda akses Admin dan User.", "Middleware Code", "Screenshot Code Middleware"),
            ("Implementasi logika redirect otomatis berdasarkan role.", "Fitur Redirect", "Screenshot Uji Coba Login"),
            ("Pengujian keamanan login dan validasi input user.", "Hasil Test Login", "Screenshot Validasi Error")
        ]
    },
    5: { # Sep 1 - 5
        "theme": "Perencanaan Sprint 2, analisis, dan desain formulir data FASUM.",
        "tasks": [
            ("Evaluasi hasil Sprint 1 dan breakdown backlog Sprint 2.", "List Backlog", "Foto Catatan/Trello"),
            ("Analisis kebutuhan atribut data FASUM dan FASOS.", "Dokumen Atribut Data", "Screenshot Catatan Teknis"),
            ("Merancang skema database (ERD) untuk tabel FASUM.", "Desain ERD", "Screenshot ERD"),
            ("Mendesain mockup formulir input data FASUM.", "Mockup Form", "Screenshot Desain Form"),
            ("Validasi desain formulir dengan standar data dinas.", "Desain Terkonfirmasi", "Foto Diskusi/Chat")
        ]
    },
    6: { # Sep 8 - 12
        "theme": "Implementasi fitur Manajemen Data FASUM pada sisi pengguna.",
        "tasks": [
            ("Membuat Migration dan Model untuk tabel data FASUM.", "Tabel Database", "Screenshot Migration Code"),
            ("Implementasi halaman formulir tambah data FASUM.", "Form Input Web", "Screenshot Halaman Form"),
            ("Menambahkan validasi input server-side pada form.", "Validasi Backend", "Screenshot Pesan Error"),
            ("Implementasi fitur upload foto pada data FASUM.", "Fitur Upload", "Screenshot Code Upload"),
            ("Membuat tampilan daftar (List View) FASUM user.", "Halaman List Data", "Screenshot Halaman List")
        ]
    },
    7: { # Sep 15 - 19
        "theme": "Lanjutan coding fitur FASUM dan validasi tampilan data.",
        "tasks": [
            ("Implementasi fitur Edit dan Update data FASUM.", "Fitur Edit Berjalan", "Screenshot Form Edit"),
            ("Implementasi fitur Hapus (Soft Delete) data FASUM.", "Fitur Delete Berjalan", "Screenshot Database (Deleted_at)"),
            ("Integrasi peta (Leaflet/Gmaps) untuk lokasi FASUM.", "Peta Interaktif", "Screenshot Tampilan Peta"),
            ("Perbaikan tampilan detail FASUM agar informatif.", "Halaman Detail", "Screenshot Halaman Detail"),
            ("Validasi akhir alur CRUD FASUM sisi pengguna.", "Fitur CRUD Stabil", "Screenshot Uji Coba CRUD")
        ]
    },
    8: { # Sep 22 - 26
        "theme": "Pengujian fungsionalitas dan integrasi fitur FASUM.",
        "tasks": [
            ("Pengujian input data FASUM dengan variasi data (Edge Cases).", "Log Pengujian", "Screenshot Tabel Data"),
            ("Memeriksa konsistensi data di database.", "Data Valid", "Screenshot Query Database"),
            ("Perbaikan bug input lokasi/peta.", "Bug Fixed", "Screenshot Perbaikan"),
            ("Optimasi query database untuk load data.", "Query Teroptimasi", "Screenshot Profiler/Query Log"),
            ("Demo fitur FASUM ke tim untuk feedback.", "Catatan Feedback", "Foto Kegiatan Demo")
        ]
    },
    9: { # Sep 29 - Oct 3
        "theme": "Perencanaan Sprint 3, desain database, dan alur CRUD Admin.",
        "tasks": [
            ("Planning Sprint 3: Fokus modul Administrator.", "Rencana Kerja", "Screenshot Backlog"),
            ("Analisis kebutuhan fitur moderasi data FASUM oleh Admin.", "Dokumen Analisis", "Catatan Analisis"),
            ("Merancang tabel database untuk modul Pengaduan.", "Schema Pengaduan", "Screenshot Desain DB"),
            ("Mendesain UI kelola FASUM & Pengaduan di Admin Panel.", "Mockup Admin Panel", "Screenshot Desain UI"),
            ("Finalisasi alur kerja verifikasi data oleh Admin.", "Flowchart Verifikasi", "Screenshot Flowchart")
        ]
    },
    10: { # Oct 6 - 10
        "theme": "Implementasi CRUD Admin untuk modul FASUM.",
        "tasks": [
            ("Membuat Controller & Route Admin untuk manajemen FASUM.", "Backend Admin", "Screenshot Code Controller"),
            ("Implementasi tabel data FASUM di Admin (Filament/Basic).", "Tabel Admin", "Screenshot Halaman Admin"),
            ("Menambahkan fitur verifikasi/persetujuan data FASUM.", "Tombol Verifikasi", "Screenshot Aksi Verifikasi"),
            ("Membuat fitur cetak laporan rekap data FASUM sederhana.", "Fitur Print", "Screenshot Tombol Print"),
            ("Testing akses kontrol (Admin Only).", "Security Check", "Screenshot Access Denied User")
        ]
    },
    11: { # Oct 13 - 17
        "theme": "Lanjutan CRUD Admin dan pembuatan fitur respon Pengaduan.",
        "tasks": [
            ("Inisialisasi modul Pengaduan (Model & Migration).", "Tabel Pengaduan", "Screenshot Migration"),
            ("Membuat formulir pengiriman pengaduan di publik.", "Form Pengaduan", "Screenshot Form User"),
            ("Membuat halaman inbox pengaduan di Dashboard Admin.", "Inbox Admin", "Screenshot Halaman Inbox"),
            ("Implementasi fitur 'Balas/Respon' pengaduan admin.", "Form Respon", "Screenshot Form Balas"),
            ("Menambahkan status pelacakan (Dikirim, Diproses, Selesai).", "Status Logic", "Screenshot Perubahan Status")
        ]
    },
    12: { # Oct 20 - 24
        "theme": "Penyelesaian coding Pengaduan (Admin) dan integrasi data.",
        "tasks": [
            ("Menambahkan notifikasi email/alert pengaduan baru.", "Notifikasi System", "Screenshot Alert"),
            ("Integrasi data pengaduan dengan wilayah (Kecamatan).", "Relasi Data", "Screenshot Filter Wilayah"),
            ("Polishing UI halaman detail pengaduan Admin.", "UI Detail Rapi", "Screenshot UI Detail"),
            ("Simulasi alur pengaduan User -> Admin.", "Flow Sukses", "Screenshot Alur Tes"),
            ("Perbaikan bug pada update status pengaduan.", "Bug Fixed", "Screenshot Code Fix")
        ]
    },
    13: { # Nov 3 - 7
        "theme": "Finalisasi coding fitur Admin (Fasum & Pengaduan) sebelum pengujian.",
        "tasks": [
            ("Code review menyeluruh pada modul Admin.", "Catatan Review", "Screenshot Code"),
            ("Standarisasi kode dan komentar dokumentasi.", "Dokumentasi Code", "Screenshot Komentar Code"),
            ("Pengecekan keamanan SQL Injection/XSS.", "Security Log", "Screenshot Uji Keamanan"),
            ("Penyesuaian tampilan Admin (UX Improvement).", "UI Improved", "Screenshot UI Baru"),
            ("Persiapan skenario pengujian Blackbox.", "Dokumen Skenario", "File Excel Skenario")
        ]
    },
    14: { # Nov 10 - 14
        "theme": "Blackbox testing untuk memastikan fungsi Admin Panel.",
        "tasks": [
            ("Blackbox Testing Modul Manajemen User.", "Log Testing User", "Screenshot Tabel Test"),
            ("Blackbox Testing Modul Fasum.", "Log Testing Fasum", "Screenshot Tabel Test"),
            ("Blackbox Testing Modul Pengaduan.", "Log Testing Pengaduan", "Screenshot Tabel Test"),
            ("Pencatatan bug dan error ke log pengujian.", "List Bug", "Screenshot List Error"),
            ("Perbaikan prioritas tinggi hasil testing.", "Bug Fixed", "Screenshot Commit Fix")
        ]
    },
    15: { # Nov 17 - 21
        "theme": "Perencanaan sprint akhir dan logika kalkulasi Statistik Dashboard.",
        "tasks": [
            ("Evaluasi akhir fitur fungsional, prep Sprint Dashboard.", "Plan Sprint Akhir", "Catatan Evaluasi"),
            ("Analisis kebutuhan visualisasi data (Charts).", "Req Visualisasi", "Screenshot Referensi Chart"),
            ("Merancang query agregasi total FASUM per wilayah.", "Query SQL", "Screenshot Query SQL"),
            ("Integrasi library Chart (ApexCharts/Chart.js).", "Chart Library Installed", "Screenshot Code Import"),
            ("Prototype widget statistik di Dashboard utama.", "Widget Stat", "Screenshot Dashboard Awal")
        ]
    },
    16: { # Nov 24 - 28
        "theme": "Implementasi fitur Statistik dashboard dan Ekspor/Impor Excel.",
        "tasks": [
            ("Implementasi Widget Total FASUM Real-time.", "Widget Counter", "Screenshot Widget"),
            ("Membuat Grafik Batang/Pie Sebaran FASUM.", "Grafik Statistik", "Screenshot Grafik"),
            ("Implementasi Export Laporan FASUM ke Excel.", "File Excel Output", "Screenshot Tombol Export"),
            ("Implementasi Export Pengaduan ke PDF/Excel.", "File Laporan", "Screenshot Hasil PDF"),
            ("Pengujian akurasi data statistik dan export.", "Validasi Data", "Screenshot Cek Data")
        ]
    },
    17: { # Dec 1 - 5
        "theme": "Lanjutan coding Statistik, Polishing, dan Penyusunan Laporan PKL.",
        "tasks": [
            ("Menambahkan filter periode pada dashboard statistik.", "Filter Berfungsi", "Screenshot Filter"),
            ("Polishing akhir antarmuka (font/warna/spacing).", "UI Final", "Screenshot UI Final"),
            ("Mulai menyusun Bab 1 (Pendahuluan) Laporan PKL.", "Draft Bab 1", "Screenshot Dokumen Word"),
            ("Menyusun Bab 2 (Profil Instansi) Laporan PKL.", "Draft Bab 2", "Screenshot Dokumen Word"),
            ("Menyusun Bab 3 (Landasan Teori) Laporan PKL.", "Draft Bab 3", "Screenshot Dokumen Word")
        ]
    },
    18: { # Dec 8 - 12
        "theme": "End-to-End Testing, presentasi, dan Finalisasi Laporan.",
        "tasks": [
            ("Simulasi full system End-to-End.", "Sistem Teruji", "Screenshot Full Flow"),
            ("Menyusun Bab 4 (Pembahasan) Laporan PKL.", "Draft Bab 4", "Screenshot Dokumen Word"),
            ("Backup database dan source code final.", "File Backup", "Screenshot Folder Backup"),
            ("Persiapan slide presentasi/demo aplikasi.", "Slide PPT", "Screenshot Slide"),
            ("Presentasi hasil magang dan serah terima.", "Berita Acara", "Foto Kegiatan Presentasi")
        ]
    }
}

extra_week_tasks = [
    ("Refactoring kode module Admin agar lebih efisien.", "Code Refactored", "Screenshot Code Compare"),
    ("Optimasi query database untuk dashboard admin.", "Query Faster", "Screenshot Query Analyzer"),
    ("Update dokumentasi teknis API/Controller.", "Dokumentasi Update", "Screenshot Dokumen"),
    ("Backup data sementara dan maintenance database.", "Backup Data", "Screenshot Folder Backup"),
    ("Koordinasi dengan pembimbing untuk review progres bulanan.", "Catatan Review", "Foto Diskusi")
]

# Build Date Map
week_map = {}
current_monday = date(2025, 8, 4)
week_id = 1

while current_monday <= date(2025, 12, 8):
    if current_monday == date(2025, 10, 27):
        week_map[current_monday] = {
            "cp": "S9", # Refactoring -> Responsibility
            "tasks": extra_week_tasks
        }
    else:
        if week_id in weekly_plan:
             data = weekly_plan[week_id]
             data['cp'] = wk_cp_map.get(week_id, "S9") # Fallback S9
             week_map[current_monday] = data
        week_id += 1
    
    current_monday += timedelta(days=7)

# Day 1
day_1_friday = date(2025, 8, 1)

output_md = []
output_md.append("# Logbook Harian PKL SIKC 2025")
output_md.append("\n**Catatan: Capaian Pembelajaran hanya dipilih satu yang paling relevan per hari.**\n")
output_md.append("| Hari Ke- | Tanggal | Capaian Pembelajaran | Aktivitas yang dilakukan | Output | Dokumentasi Bukti Kegiatan |")
output_md.append("| :---: | :--- | :--- | :--- | :--- | :--- |")

current_day = start_date
day_count = 1

while current_day <= end_date:
    date_formatted = f"{current_day.day} {current_day.strftime('%B')} {current_day.year}"
    # Simple formatting adjustment for month Indonesian mapping
    months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
    m_idx = current_day.month - 1
    date_formatted = f"{current_day.day} {months[m_idx]} {current_day.year}"
    
    is_weekend = current_day.weekday() >= 5 
    
    activity = ""
    out = ""
    docs = ""
    cp = ""
    
    if current_day == day_1_friday:
        activity = "Perkenalan instansi, pengarahan dari pembimbing lapangan, dan setup lingkungan kerja awal."
        out = "Paham jobdesk awal"
        docs = "Foto Pengarahan/Kantor"
        cp = "S7" # Taat hukum/Disiplin
        
    elif is_weekend:
        activity = "Libur"
        out = "-"
        docs = "-"
        cp = "-"
    else:
        # Determine Week
        monday_of_week = current_day - timedelta(days=current_day.weekday())
        
        if monday_of_week in week_map:
            week_data = week_map[monday_of_week]
            cp = week_data['cp']
            task_idx = current_day.weekday()
            if 0 <= task_idx < len(week_data['tasks']):
                t_act, t_out, t_doc = week_data['tasks'][task_idx]
                activity = t_act
                out = t_out
                docs = t_doc
            else:
                activity = "Kegiatan Rutin"
                out = "-"
                docs = "-"
        else:
             if current_day >= date(2025, 12, 15):
                 activity = "Penyerahan Laporan dan Penyelesaian Administrasi PKL."
                 out = "Laporan Diserahkan"
                 docs = "Foto Penyerahan"
                 cp = "KU4" 
             else:
                 activity = "Kegiatan Harian"
                 out = "-"
                 docs = "-"
                 cp = "-"

    row = f"| {day_count} | {date_formatted} | {cp} | {activity} | {out} | {docs} |"
    output_md.append(row)
    
    current_day += timedelta(days=1)
    day_count += 1

out_path = "Logbook_Harian_Final_Canvas.md"
with open(out_path, "w", encoding="utf-8") as f:
    f.write("\n".join(output_md))

print(f"Artifact generated at {out_path}")
