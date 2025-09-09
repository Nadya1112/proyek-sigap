@extends('layouts.public')

@section('title', 'Informasi FASUM - SIGAP KOMPLEK')

@section('content')
    <!-- Page Header -->
    <header class="page-header">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-4xl mx-auto">
                <div class="header-badge">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Informasi FASUM
                </div>
                <h1 class="page-header-title">Data Prasarana, Sarana & Utilitas Umum</h1>
                <p class="page-header-desc">
                    Temukan informasi lengkap mengenai status FASUM komplek perumahan di Kota Banjarmasin.
                    Data real-time yang akurat dan terpercaya untuk kemudahan masyarakat.
                </p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                    <div class="quick-stat">
                        <div class="stat-number">{{ $totalKomplek ?? '0' }}</div>
                        <div class="stat-label">Total Komplek</div>
                    </div>
                    <div class="quick-stat">
                        <div class="stat-number">{{ $sudahDiserahkan ?? '0' }}</div>
                        <div class="stat-label">Sudah Diserahkan</div>
                    </div>
                    <div class="quick-stat">
                        <div class="stat-number">{{ $prosesPenyerahan ?? '0' }}</div>
                        <div class="stat-label">Proses Penyerahan</div>
                    </div>
                    <div class="quick-stat">
                        <div class="stat-number">{{ $belumDiserahkan ?? '0' }}</div>
                        <div class="stat-label">Belum Diserahkan</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-8 lg:py-16 bg-gray-50">
        <div class="container mx-auto px-6">

            <!-- Filter Section -->
            <div class="filter-card">
                <div class="filter-header">
                    <h2 class="filter-title">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z">
                            </path>
                        </svg>
                        Filter Data
                    </h2>
                    <p class="filter-subtitle">Gunakan filter untuk mempersempit hasil pencarian</p>
                </div>

                <form action="{{ route('informasi-fasum') }}" method="GET" class="filter-form">
                    <!-- Filter Row 1 -->
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="kecamatan" class="form-label">
                                <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                </svg>
                                Kecamatan
                            </label>
                            <select id="kecamatan" name="kecamatan" class="form-select">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}"
                                        {{ $request->input('kecamatan') == $kecamatan->id ? 'selected' : '' }}>
                                        {{ $kecamatan->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="kelurahan" class="form-label">
                                <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Kelurahan
                            </label>
                            <select id="kelurahan" name="kelurahan" class="form-select"
                                {{ !$request->input('kecamatan') ? 'disabled' : '' }}>
                                <option value="">Pilih Kelurahan</option>
                                @foreach ($kelurahans as $kelurahan)
                                    <option value="{{ $kelurahan->id }}"
                                        {{ $request->input('kelurahan') == $kelurahan->id ? 'selected' : '' }}>
                                        {{ $kelurahan->nama_kelurahan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="status" class="form-label">
                                <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status Aset
                            </label>
                            <select id="status" name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="Sudah Diserahkan"
                                    {{ $request->input('status') == 'Sudah Diserahkan' ? 'selected' : '' }}>Sudah
                                    Diserahkan</option>
                                <option value="Proses Penyerahan"
                                    {{ $request->input('status') == 'Proses Penyerahan' ? 'selected' : '' }}>Proses
                                    Penyerahan</option>
                                <option value="Belum Diserahkan"
                                    {{ $request->input('status') == 'Belum Diserahkan' ? 'selected' : '' }}>Belum
                                    Diserahkan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filter Row 2 -->
                    <div class="filter-row">
                        <div class="filter-group lg:col-span-2">
                            <label for="search" class="form-label">
                                <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Cari Komplek
                            </label>
                            <div class="search-input-wrapper">
                                <input type="text" id="search" name="search" class="form-input search-input"
                                    placeholder="Masukkan nama komplek atau alamat..."
                                    value="{{ $request->input('search') }}">
                                <div class="search-icon">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="btn-filter-apply">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z">
                                    </path>
                                </svg>
                                Terapkan Filter
                            </button>

                            <a href="{{ route('informasi-fasum') }}" class="btn-filter-reset">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Info -->
            <div class="results-info">
                <div class="results-summary">
                    <span class="results-count">{{ $kompleks->total() }}</span> komplek ditemukan
                    @if ($request->hasAny(['kecamatan', 'kelurahan', 'status', 'search']))
                        <span class="results-filtered">dengan filter aktif</span>
                    @endif
                </div>

                <div class="results-actions">
                    <button id="toggleView" class="view-toggle" data-view="table">
                        <svg class="w-4 h-4 view-icon-table" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 6h18m-9 8h9m-9 4h9m-9-8h9"></path>
                        </svg>
                        <svg class="w-4 h-4 view-icon-grid hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        <span class="view-label">Tampilan Grid</span>
                    </button>

                    <button id="exportBtn" class="export-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            <!-- Data Table -->
            <div class="data-container mb-3">
                <!-- Table View -->
                <div id="tableView" class="data-table">
                    <div class="table-wrapper">
                        <table class="data-table-element">
                            <thead>
                                <tr>
                                    <th class="table-th">
                                        <div class="th-content">
                                            <span>No.</span>
                                        </div>
                                    </th>
                                    <th class="table-th">
                                        <div class="th-content">
                                            <span>Komplek Perumahan</span>
                                            <svg class="sort-icon" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                            </svg>
                                        </div>
                                    </th>
                                    <th class="table-th">
                                        <div class="th-content">
                                            <span>Lokasi</span>
                                        </div>
                                    </th>
                                    <th class="table-th">
                                        <div class="th-content">
                                            <span>Status Aset</span>
                                        </div>
                                    </th>
                                    <th class="table-th">
                                        <div class="th-content">
                                            <span>Sertifikat</span>
                                        </div>
                                    </th>
                                    <th class="table-th">
                                        <div class="th-content">
                                            <span>Aksi</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kompleks as $index => $item)
                                    <tr class="table-row">
                                        <td class="table-td text-center">
                                            <span
                                                class="row-number">{{ ($kompleks->currentPage() - 1) * $kompleks->perPage() + $index + 1 }}</span>
                                        </td>
                                        <td class="table-td">
                                            <div class="komplek-info">
                                                <div class="komplek-image">
                                                    <img src="{{ $item->foto_komplek ? asset('storage/' . $item->foto_komplek) : asset('img/placeholder-komplek.jpg') }}"
                                                        alt="Foto {{ $item->nama_komplek }}" loading="lazy">
                                                </div>
                                                <div class="komplek-details">
                                                    <h3 class="komplek-name">{{ $item->nama_komplek }}</h3>
                                                    <p class="komplek-address">{{ Str::limit($item->alamat, 50) }}</p>
                                                    @if ($item->nomor)
                                                        <span class="komplek-number">No. {{ $item->nomor }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            <div class="location-info">
                                                <div class="location-item">
                                                    <svg class="location-icon" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5">
                                                        </path>
                                                    </svg>
                                                    {{ $item->kelurahan->nama_kelurahan }}
                                                </div>
                                                <div class="location-item">
                                                    <svg class="location-icon" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                        </path>
                                                    </svg>
                                                    {{ $item->kelurahan->kecamatan->nama_kecamatan }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            <span
                                                class="status-badge status-{{ str_replace(' ', '-', strtolower($item->status_aset)) }}">
                                                <span class="status-dot"></span>
                                                {{ $item->status_aset }}
                                            </span>
                                        </td>
                                        <td class="table-td text-center">
                                            <div class="certificate-count">
                                                <span class="count-number">{{ $item->jumlah_sertifikat }}</span>
                                                <span class="count-label">sertifikat</span>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            <div class="action-buttons">
                                                <button class="action-btn action-btn-view"
                                                    onclick="viewDetail({{ $item->id }})" title="Lihat Detail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                <button class="action-btn action-btn-map"
                                                    onclick="showMap({{ $item->latitude ?? 0 }}, {{ $item->longitude ?? 0 }})"
                                                    title="Lihat Peta">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="empty-state">
                                            <div class="empty-state-content">
                                                <div class="empty-icon">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <h3 class="empty-title">Data tidak ditemukan</h3>
                                                <p class="empty-description">Tidak ada komplek yang sesuai dengan kriteria
                                                    pencarian Anda. Coba ubah filter atau kata kunci pencarian.</p>
                                                <a href="{{ route('informasi.fasum') }}" class="empty-action">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                        </path>
                                                    </svg>
                                                    Reset Pencarian
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Grid View (Hidden by default) -->
                <div id="gridView" class="data-grid hidden">
                    <div class="grid-container">
                        @forelse ($kompleks as $item)
                            <div class="grid-card">
                                <div class="grid-card-header">
                                    <img src="{{ $item->foto_komplek ? asset('storage/' . $item->foto_komplek) : asset('img/placeholder-komplek.jpg') }}"
                                        alt="Foto {{ $item->nama_komplek }}" class="grid-card-image" loading="lazy">
                                    <div class="grid-card-status">
                                        <span
                                            class="status-badge status-{{ str_replace(' ', '-', strtolower($item->status_aset)) }}">
                                            <span class="status-dot"></span>
                                            {{ $item->status_aset }}
                                        </span>
                                    </div>
                                </div>
                                <div class="grid-card-body">
                                    <h3 class="grid-card-title">{{ $item->nama_komplek }}</h3>
                                    <p class="grid-card-address">{{ Str::limit($item->alamat, 60) }}</p>

                                    <div class="grid-card-info">
                                        <div class="info-item">
                                            <svg class="info-icon" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                            </svg>
                                            <span>{{ $item->kelurahan->nama_kelurahan }},
                                                {{ $item->kelurahan->kecamatan->nama_kecamatan }}</span>
                                        </div>
                                        <div class="info-item">
                                            <svg class="info-icon" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <span>{{ $item->jumlah_sertifikat }} Sertifikat</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid-card-footer">
                                    <button class="grid-action-btn grid-btn-primary"
                                        onclick="viewDetail({{ $item->id }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Lihat Detail
                                    </button>
                                    <button class="grid-action-btn grid-btn-secondary"
                                        onclick="showMap({{ $item->latitude ?? 0 }}, {{ $item->longitude ?? 0 }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                        </svg>
                                        Peta
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="grid-empty-state">
                                <div class="empty-state-content">
                                    <div class="empty-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="empty-title">Data tidak ditemukan</h3>
                                    <p class="empty-description">Tidak ada komplek yang sesuai dengan kriteria pencarian
                                        Anda.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if ($kompleks->hasPages())
                <div class="pagination-wrapper">
                    {{ $kompleks->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
    </main>

    <style>
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #F59E0B 0%, #F97316 50%, #EA580C 100%);
            padding: 3rem 0 4rem;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,1000 1000,0 1000,1000"/></svg>');
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-header-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .page-header-desc {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.125rem;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Quick Stats */
        .quick-stat {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .quick-stat:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .stat-number {
            color: white;
            font-size: 1.5rem;
            font-weight: 800;
            display: block;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.25rem;
        }

        /* Filter Card */
        .filter-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .filter-header {
            background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #E2E8F0;
        }

        .filter-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.125rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0;
        }

        .filter-subtitle {
            color: #64748B;
            font-size: 0.875rem;
            margin: 0.5rem 0 0 0;
        }

        .filter-form {
            padding: 1.5rem;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-row:last-child {
            margin-bottom: 0;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            align-items: end;
        }

        /* Form Elements */
        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }

        .label-icon {
            width: 1rem;
            height: 1rem;
            color: #6B7280;
        }

        .form-select,
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: white;
        }

        .form-select:focus,
        .form-input:focus {
            border-color: #F59E0B;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
            outline: none;
        }

        .form-select:disabled {
            background-color: #F9FAFB;
            color: #9CA3AF;
            cursor: not-allowed;
        }

        .search-input-wrapper {
            position: relative;
        }

        .search-input {
            padding-left: 2.5rem;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        /* Filter Buttons */
        .btn-filter-apply {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #F59E0B, #F97316);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            border: none;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 140px;
        }

        .btn-filter-apply:hover {
            background: linear-gradient(135deg, #D97706, #EA580C);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
        }

        .btn-filter-reset {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: white;
            color: #6B7280;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            border: 1px solid #D1D5DB;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s ease;
            min-width: 100px;
        }

        .btn-filter-reset:hover {
            background: #F9FAFB;
            border-color: #9CA3AF;
            color: #374151;
        }

        /* Results Info */
        .results-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding: 1rem 1.5rem;
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #E5E7EB;
        }

        .results-summary {
            font-size: 0.875rem;
            color: #6B7280;
        }

        .results-count {
            font-weight: 700;
            color: #F59E0B;
            font-size: 1rem;
        }

        .results-filtered {
            color: #10B981;
            font-weight: 500;
        }

        .results-actions {
            display: flex;
            gap: 0.75rem;
        }

        .view-toggle,
        .export-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid #D1D5DB;
            background: white;
            color: #6B7280;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .view-toggle:hover,
        .export-btn:hover {
            border-color: #F59E0B;
            color: #F59E0B;
            background: #FFFBEB;
        }

        /* Data Container */
        .data-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* Table Styles */
        .table-wrapper {
            overflow-x: auto;
        }

        .data-table-element {
            width: 100%;
            border-collapse: collapse;
        }

        .table-th {
            background: #F8FAFC;
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid #E2E8F0;
        }

        .th-content {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sort-icon {
            width: 1rem;
            height: 1rem;
            color: #CBD5E1;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .sort-icon:hover {
            color: #F59E0B;
        }

        .table-row {
            border-bottom: 1px solid #F1F5F9;
            transition: background-color 0.2s ease;
        }

        .table-row:hover {
            background: #FAFAFA;
        }

        .table-td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #374151;
            vertical-align: top;
        }

        .row-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            background: #F3F4F6;
            color: #6B7280;
            border-radius: 50%;
            font-weight: 600;
            font-size: 0.75rem;
        }

        /* Komplek Info */
        .komplek-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .komplek-image {
            flex-shrink: 0;
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            overflow: hidden;
            background: #F3F4F6;
        }

        .komplek-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .komplek-details {
            min-width: 0;
        }

        .komplek-name {
            font-weight: 700;
            color: #111827;
            margin: 0 0 0.25rem 0;
            font-size: 0.875rem;
        }

        .komplek-address {
            color: #6B7280;
            font-size: 0.75rem;
            margin: 0;
            line-height: 1.4;
        }

        .komplek-number {
            display: inline-block;
            background: #DBEAFE;
            color: #1E40AF;
            font-size: 0.625rem;
            font-weight: 600;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            margin-top: 0.25rem;
        }

        /* Location Info */
        .location-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .location-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: #6B7280;
        }

        .location-icon {
            width: 0.875rem;
            height: 0.875rem;
            color: #9CA3AF;
            flex-shrink: 0;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: capitalize;
        }

        .status-dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .status-sudah-diserahkan {
            background: #DCFCE7;
            color: #166534;
        }

        .status-sudah-diserahkan .status-dot {
            background: #22C55E;
        }

        .status-belum-diserahkan {
            background: #FEE2E2;
            color: #991B1B;
        }

        .status-belum-diserahkan .status-dot {
            background: #EF4444;
        }

        .status-proses-penyerahan {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-proses-penyerahan .status-dot {
            background: #F59E0B;
        }

        /* Certificate Count */
        .certificate-count {
            text-align: center;
        }

        .count-number {
            display: block;
            font-size: 1.5rem;
            font-weight: 800;
            color: #F59E0B;
            line-height: 1;
        }

        .count-label {
            font-size: 0.625rem;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 0.375rem;
            border: 1px solid;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn-view {
            border-color: #3B82F6;
            color: #3B82F6;
        }

        .action-btn-view:hover {
            background: #3B82F6;
            color: white;
        }

        .action-btn-map {
            border-color: #10B981;
            color: #10B981;
        }

        .action-btn-map:hover {
            background: #10B981;
            color: white;
        }

        /* Grid View */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .grid-card {
            border: 1px solid #E5E7EB;
            border-radius: 0.75rem;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
        }

        .grid-card:hover {
            border-color: #F59E0B;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .grid-card-header {
            position: relative;
            height: 12rem;
            overflow: hidden;
        }

        .grid-card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .grid-card-status {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
        }

        .grid-card-body {
            padding: 1.25rem;
        }

        .grid-card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 0.5rem 0;
        }

        .grid-card-address {
            color: #6B7280;
            font-size: 0.875rem;
            margin: 0 0 1rem 0;
            line-height: 1.4;
        }

        .grid-card-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: #6B7280;
        }

        .info-icon {
            width: 1rem;
            height: 1rem;
            color: #9CA3AF;
            flex-shrink: 0;
        }

        .grid-card-footer {
            display: flex;
            gap: 0.5rem;
            padding: 1rem 1.25rem;
            border-top: 1px solid #F3F4F6;
            background: #FAFAFA;
        }

        .grid-action-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
        }

        .grid-btn-primary {
            background: #F59E0B;
            color: white;
        }

        .grid-btn-primary:hover {
            background: #D97706;
        }

        .grid-btn-secondary {
            background: white;
            color: #6B7280;
            border: 1px solid #D1D5DB;
        }

        .grid-btn-secondary:hover {
            background: #F9FAFB;
            border-color: #9CA3AF;
        }

        /* Empty State */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state-content {
            max-width: 400px;
            margin: 0 auto;
        }

        .empty-icon {
            width: 4rem;
            height: 4rem;
            margin: 0 auto 1.5rem;
            color: #D1D5DB;
        }

        .empty-icon svg {
            width: 100%;
            height: 100%;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #374151;
            margin: 0 0 0.5rem 0;
        }

        .empty-description {
            color: #6B7280;
            margin: 0 0 1.5rem 0;
            line-height: 1.6;
        }

        .empty-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #F59E0B;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .empty-action:hover {
            color: #D97706;
        }

        .grid-empty-state {
            grid-column: 1 / -1;
            padding: 4rem 2rem;
            text-align: center;
        }

        /* Pagination */
        .pagination-wrapper {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header-title {
                font-size: 2rem;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .results-info {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .results-actions {
                justify-content: center;
            }

            .grid-container {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .komplek-info {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
        }

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>

    <script>
        // Toggle between table and grid view
        document.getElementById('toggleView').addEventListener('click', function() {
            const tableView = document.getElementById('tableView');
            const gridView = document.getElementById('gridView');
            const toggleBtn = this;
            const viewLabel = toggleBtn.querySelector('.view-label');
            const tableIcon = toggleBtn.querySelector('.view-icon-table');
            const gridIcon = toggleBtn.querySelector('.view-icon-grid');

            if (tableView.classList.contains('hidden')) {
                // Show table view
                tableView.classList.remove('hidden');
                gridView.classList.add('hidden');
                viewLabel.textContent = 'Tampilan Grid';
                tableIcon.classList.remove('hidden');
                gridIcon.classList.add('hidden');
                toggleBtn.setAttribute('data-view', 'table');
            } else {
                // Show grid view
                tableView.classList.add('hidden');
                gridView.classList.remove('hidden');
                viewLabel.textContent = 'Tampilan Tabel';
                tableIcon.classList.add('hidden');
                gridIcon.classList.remove('hidden');
                toggleBtn.setAttribute('data-view', 'grid');
            }
        });

        // Kecamatan-Kelurahan dependency (AJAX)
        document.getElementById('kecamatan').addEventListener('change', async function() {
            const kecamatanId = this.value;
            const kelurahanSelect = document.getElementById('kelurahan');

            // Reset dropdown kelurahan
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kelurahanSelect.value = '';
            kelurahanSelect.disabled = true;

            if (!kecamatanId) return;

            try {
                // Contoh pembuatan URL dinamis dari route()
                const url = "{{ route('kelurahan.byKecamatan', ':id') }}".replace(':id', kecamatanId);
                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!res.ok) throw new Error('Gagal memuat data kelurahan');
                const data = await res.json();

                // Populate opsi kelurahan
                data.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k.id;
                    opt.textContent = k.nama_kelurahan;
                    kelurahanSelect.appendChild(opt);
                });

                kelurahanSelect.disabled = false;
            } catch (e) {
                console.error(e);
                alert('Tidak bisa memuat daftar kelurahan. Coba lagi.');
            }
        });

        // (opsional) Saat halaman pertama kali load, jika ada kecamatan terpilih dari query string,
        // dropdown kelurahan sudah diload server-side via controller.
        // Kalau mau force refresh lewat AJAX juga, bisa panggil event change:
        @if ($request->filled('kecamatan') && $kelurahans->isEmpty())
            document.addEventListener('DOMContentLoaded', () => {
                const kecSel = document.getElementById('kecamatan');
                if (kecSel.value) {
                    const event = new Event('change');
                    kecSel.dispatchEvent(event);
                }
            });
        @endif

        // Search with debounce
        let searchTimeout;
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value;

            searchTimeout = setTimeout(() => {
                // You can implement live search here
                console.log('Searching for:', searchTerm);
            }, 500);
        });

        // Export functionality
        document.getElementById('exportBtn').addEventListener('click', function() {
            // Implement export functionality
            alert('Export functionality will be implemented');
        });

        // View detail function
        function viewDetail(id) {
            // Implement view detail functionality
            console.log('View detail for komplek ID:', id);
            // You can redirect to detail page or show modal
        }

        // Show map function
        function showMap(lat, lng) {
            if (lat === 0 && lng === 0) {
                alert('Koordinat lokasi tidak tersedia untuk komplek ini.');
                return;
            }

            // Open Google Maps
            const url = `https://www.google.com/maps?q=${lat},${lng}`;
            window.open(url, '_blank');
        }

        // Add loading states to buttons
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.filter-form');
            const submitBtn = document.querySelector('.btn-filter-apply');

            form.addEventListener('submit', function() {
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;
                submitBtn.disabled = true;
            });
        });
    </script>
@endsection
