@extends('layouts.public')

@section('title', 'SIGAP KOMPLEK')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/informasi-fasum.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/informasi-fasum.js') }}" defer></script>
@endpush

@section('content')
    <section class="relative overflow-hidden">
        <div class="relative"
            style="background: linear-gradient(135deg, #F7A623 0%, #FF7A00 45%, #F25C3B 70%, #F04949 100%);">
            {{-- glow kiri-atas --}}
            <div class="pointer-events-none absolute -top-6 -left-10 w-[360px] h-[360px] opacity-70"
                style="background: radial-gradient(closest-side, rgba(255,179,73,0.55) 0%, rgba(255,179,73,0.26) 34%, rgba(255,179,73,0.10) 60%, transparent 72%); filter: blur(2px);">
            </div>
            {{-- glow kanan-bawah --}}
            <div class="pointer-events-none absolute -bottom-10 -right-8 w-[340px] h-[340px] opacity-65"
                style="background: radial-gradient(closest-side, rgba(255,120,120,0.45) 0%, rgba(255,120,120,0.22) 35%, rgba(255,120,120,0.10) 58%, transparent 75%); filter: blur(2px);">
            </div>

            <div class="container mx-auto px-6 py-14 md:py-20 relative text-white">
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
        </div>
    </section>

    <main class="py-8 lg:py-16 bg-gray-50">
        <div class="container mx-auto px-6">

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
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        <span class="view-label">Tampilan Grid</span>
                    </button>
                </div>
            </div>

            <div class="data-container mb-3">
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
                                                <a href="{{ route('sebaran', ['location' => $item->id]) }}"
                                                    class="action-btn action-btn-map" title="Lihat Peta">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                </a>
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
                                                <a href="{{ route('informasi-fasum') }}" class="empty-action">
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
                                    <a href="{{ route('sebaran', ['location' => $item->id]) }}"
                                        class="grid-action-btn grid-btn-secondary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                        </svg>
                                        Peta
                                    </a>
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

            @if ($kompleks->hasPages())
                <div class="pagination-wrapper">
                    {{ $kompleks->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
    </main>

    <div id="detailModal" class="modal-backdrop hidden">
        <div class="modal-container">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-header-content">
                        <h3 class="modal-title" id="modal-nama-komplek"></h3>
                        <div class="modal-status-badge" id="modal-status-badge"></div>
                    </div>
                    <button class="modal-close" onclick="closeModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="modal-loading-indicator" class="hidden">
                        <div class="flex flex-col items-center justify-center py-16">
                            <svg class="animate-spin h-12 w-12 text-orange-500" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <p class="mt-4 text-lg font-semibold text-gray-600">Memuat Data...</p>
                        </div>
                    </div>
                    <div id="modal-content-data" class="hidden">
                        <!-- Image Section -->
                        <div class="modal-image-container">
                            <img id="modal-image" src="" alt="Foto Komplek" class="modal-image">
                            <div class="modal-image-overlay">
                                <div class="image-overlay-content">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="modal-stats-grid">
                            <div class="modal-stat-item">
                                <div class="stat-icon stat-icon-orange">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value" id="modal-unit">-</div>
                                    <div class="stat-label">Total Unit</div>
                                </div>
                            </div>

                            <div class="modal-stat-item">
                                <div class="stat-icon stat-icon-blue">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value" id="modal-sertifikat">-</div>
                                    <div class="stat-label">Jumlah Sertifikat</div>
                                </div>
                            </div>

                            <div class="modal-stat-item">
                                <div class="stat-icon stat-icon-green">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                        </path>
                                    </svg>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value" id="modal-aset">-</div>
                                    <div class="stat-label">Jumlah Aset</div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Section -->
                        <div class="modal-info-section">
                            <h4 class="info-section-title">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informasi Umum
                            </h4>
                            <div class="info-grid">
                                <div class="info-row">
                                    <div class="info-label">
                                        <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Pengembang
                                    </div>
                                    <div class="info-value" id="modal-pengembang">-</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">
                                        <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Alamat
                                    </div>
                                    <div class="info-value" id="modal-alamat">-</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">
                                        <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                            </path>
                                        </svg>
                                        Lokasi
                                    </div>
                                    <div class="info-value" id="modal-lokasi">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- Facilities Section -->
                        <div class="modal-facilities-section">
                            <h4 class="info-section-title">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Fasilitas
                            </h4>

                            <div class="facilities-grid">
                                <div class="facility-card">
                                    <div class="facility-header">
                                        <div class="facility-icon facility-icon-orange">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="facility-title">Fasilitas Umum</div>
                                    </div>
                                    <p class="facility-content" id="modal-fasum">-</p>
                                </div>

                                <div class="facility-card">
                                    <div class="facility-header">
                                        <div class="facility-icon facility-icon-purple">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="facility-title">Fasilitas Ibadah</div>
                                    </div>
                                    <p class="facility-content" id="modal-ibadah">-</p>
                                </div>

                                <div class="facility-card">
                                    <div class="facility-header">
                                        <div class="facility-icon facility-icon-blue">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="facility-title">Fasilitas Pendidikan</div>
                                    </div>
                                    <p class="facility-content" id="modal-pendidikan">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Existing Styles ... (Keep all your existing styles) */
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

        /* ===== MODAL STYLES - IMPROVED ===== */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.75);
            z-index: 50;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            backdrop-filter: blur(8px);
            animation: backdropFadeIn 0.3s ease-out;
        }

        @keyframes backdropFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-backdrop.hidden {
            display: none;
        }

        .modal-container {
            background: white;
            border-radius: 1.25rem;
            width: 100%;
            max-width: 60rem;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Scrollbar Styling */
        .modal-container::-webkit-scrollbar {
            width: 8px;
        }

        .modal-container::-webkit-scrollbar-track {
            background: #F1F5F9;
            border-radius: 10px;
        }

        .modal-container::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }

        .modal-container::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        /* Modal Header */
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 2rem 2rem 1.5rem;
            border-bottom: 2px solid #F1F5F9;
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
        }

        .modal-header-content {
            flex: 1;
            min-width: 0;
            padding-right: 1rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 0.75rem 0;
            line-height: 1.3;
        }

        .modal-status-badge {
            display: inline-block;
        }

        .modal-close {
            background: #F1F5F9;
            border: none;
            color: #64748B;
            cursor: pointer;
            padding: 0.625rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .modal-close:hover {
            background: #E2E8F0;
            color: #0F172A;
            transform: rotate(90deg);
        }

        .modal-close:active {
            transform: rotate(90deg) scale(0.95);
        }

        /* Modal Body */
        .modal-body {
            padding: 2rem;
        }

        /* Image Container */
        .modal-image-container {
            position: relative;
            width: 100%;
            height: 20rem;
            border-radius: 1rem;
            overflow: hidden;
            background: linear-gradient(135deg, #F1F5F9 0%, #E2E8F0 100%);
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .modal-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .modal-image-container:hover .modal-image {
            transform: scale(1.05);
        }

        .modal-image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.3) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-image-container:hover .modal-image-overlay {
            opacity: 1;
        }

        .image-overlay-content {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 1rem;
            border-radius: 50%;
            color: #F59E0B;
        }

        /* Stats Grid */
        .modal-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .modal-stat-item {
            background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
            border: 2px solid #FDE68A;
            border-radius: 1rem;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: all 0.3s ease;
        }

        .modal-stat-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.3);
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .stat-icon svg {
            width: 1.5rem;
            height: 1.5rem;
            color: white;
        }

        .stat-icon-orange {
            background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
        }

        .stat-icon-blue {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        }

        .stat-icon-green {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        .stat-content {
            width: 100%;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #EA580C;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #92400E;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Info Section */
        .modal-info-section {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.125rem;
            font-weight: 700;
            color: #0F172A;
            margin: 0 0 1.25rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #E2E8F0;
        }

        .info-section-title svg {
            color: #F97316;
        }

        .info-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .info-row {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 1rem;
            align-items: flex-start;
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
        }

        .info-icon {
            width: 1.125rem;
            height: 1.125rem;
            color: #94A3B8;
            flex-shrink: 0;
        }

        .info-value {
            font-size: 0.9375rem;
            color: #1E293B;
            font-weight: 500;
            line-height: 1.6;
            padding: 0.5rem 0.75rem;
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #E2E8F0;
        }

        /* Facilities Section */
        .modal-facilities-section {
            margin-top: 2rem;
        }

        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-top: 1.25rem;
        }

        .facility-card {
            background: white;
            border: 2px solid #E2E8F0;
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .facility-card:hover {
            border-color: #F97316;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .facility-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #F1F5F9;
        }

        .facility-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .facility-icon svg {
            width: 1.5rem;
            height: 1.5rem;
            color: white;
        }

        .facility-icon-orange {
            background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
        }

        .facility-icon-purple {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        }

        .facility-icon-blue {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        }

        .facility-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0F172A;
        }

        .facility-content {
            font-size: 0.9375rem;
            color: #475569;
            line-height: 1.7;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header-title {
                font-size: 2rem;
            }

            .modal-container {
                max-width: 100%;
                border-radius: 1rem 1rem 0 0;
                max-height: 95vh;
            }

            .modal-header {
                padding: 1.5rem 1.5rem 1rem;
            }

            .modal-title {
                font-size: 1.25rem;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .modal-image-container {
                height: 16rem;
            }

            .modal-stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                gap: 0.75rem;
            }

            .modal-stat-item {
                padding: 1rem;
            }

            .stat-icon {
                width: 2.5rem;
                height: 2.5rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .stat-label {
                font-size: 0.625rem;
            }

            .info-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .facilities-grid {
                grid-template-columns: 1fr;
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

        @media (max-width: 640px) {
            .modal-stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-icon {
                width: 2.25rem;
                height: 2.25rem;
            }

            .stat-icon svg {
                width: 1.25rem;
                height: 1.25rem;
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
                tableView.classList.remove('hidden');
                gridView.classList.add('hidden');
                viewLabel.textContent = 'Tampilan Grid';
                tableIcon.classList.remove('hidden');
                gridIcon.classList.add('hidden');
                toggleBtn.setAttribute('data-view', 'table');
            } else {
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

            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kelurahanSelect.value = '';
            kelurahanSelect.disabled = true;

            if (!kecamatanId) return;

            try {
                const url = `{{ route('get.kelurahan', ['kecamatanId' => ':kecamatanId']) }}`.replace(
                    ':kecamatanId', kecamatanId);

                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) {
                    throw new Error(`Gagal memuat data kelurahan.`);
                }

                const data = await res.json();

                data.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k.id;
                    opt.textContent = k.nama_kelurahan;
                    kelurahanSelect.appendChild(opt);
                });

                kelurahanSelect.disabled = false;
            } catch (e) {
                console.error('Error fetching kelurahan:', e);
                alert('Tidak bisa memuat daftar kelurahan. Coba lagi.');
            }
        });

        let searchTimeout;
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value;
            searchTimeout = setTimeout(() => {
                // Live search implementation
            }, 500);
        });

        function showMap(lat, lng) {
            if (lat === 0 && lng === 0) {
                alert('Koordinat lokasi tidak tersedia untuk komplek ini.');
                return;
            }
            const url = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
            window.open(url, '_blank');
        }

        // Modal Detail Function
        function viewDetail(id) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('modal-content-data');
            const loadingIndicator = document.getElementById('modal-loading-indicator');

            modal.classList.remove('hidden');

            // Tampilkan loading indicator dan sembunyikan konten
            loadingIndicator.classList.remove('hidden');
            content.classList.add('hidden');
            document.getElementById('modal-nama-komplek').textContent = 'Memuat Data...';

            fetch(`/informasi-fasum/${id}/detail`)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengambil data');
                    return response.json();
                })
                .then(data => {
                    // Set nama komplek
                    document.getElementById('modal-nama-komplek').textContent = data.nama_komplek;

                    // Set status badge
                    const statusClass = `status-${data.status_aset.toLowerCase().replace(/ /g, '-')}`;
                    document.getElementById('modal-status-badge').innerHTML = `
                        <span class="status-badge ${statusClass}">
                            <span class="status-dot"></span>
                            ${data.status_aset}
                        </span>
                    `;

                    // Set informasi umum
                    document.getElementById('modal-pengembang').textContent = data.nama_pengembang || 'Tidak tersedia';
                    document.getElementById('modal-alamat').textContent = data.alamat_komplek || 'Tidak tersedia';

                    const lokasi = (data.kelurahan ? data.kelurahan.nama_kelurahan : '-') +
                        (data.kelurahan && data.kelurahan.kecamatan ? ', ' + data.kelurahan.kecamatan
                            .nama_kecamatan : '');
                    document.getElementById('modal-lokasi').textContent = lokasi;

                    // Set statistik
                    document.getElementById('modal-sertifikat').textContent = data.jumlah_sertifikat ?? 0;
                    document.getElementById('modal-unit').textContent = data.jumlah_unit ?? 0;
                    document.getElementById('modal-aset').textContent = data.jumlah_aset ?? 0;

                    // Set fasilitas dan sembunyikan jika kosong
                    const fasumContent = data.fasilitas_umum || 'Tidak ada data';
                    const ibadahContent = data.fasilitas_ibadah || 'Tidak ada data';
                    const pendidikanContent = data.fasilitas_pendidikan || 'Tidak ada data';

                    const fasumCard = document.getElementById('modal-fasum').closest('.facility-card');
                    const ibadahCard = document.getElementById('modal-ibadah').closest('.facility-card');
                    const pendidikanCard = document.getElementById('modal-pendidikan').closest('.facility-card');
                    const facilitiesSection = document.querySelector('.modal-facilities-section');

                    document.getElementById('modal-fasum').textContent = fasumContent;
                    document.getElementById('modal-ibadah').textContent = ibadahContent;
                    document.getElementById('modal-pendidikan').textContent = pendidikanContent;

                    // Sembunyikan kartu fasilitas jika kosong
                    fasumCard.style.display = (fasumContent === 'Tidak ada data') ? 'none' : 'block';
                    ibadahCard.style.display = (ibadahContent === 'Tidak ada data') ? 'none' : 'block';
                    pendidikanCard.style.display = (pendidikanContent === 'Tidak ada data') ? 'none' : 'block';

                    // Sembunyikan seluruh section fasilitas jika semua kosong
                    const hasFacilities = fasumContent !== 'Tidak ada data' || ibadahContent !== 'Tidak ada data' ||
                        pendidikanContent !== 'Tidak ada data';
                    facilitiesSection.style.display = hasFacilities ? 'block' : 'none';

                    // Set gambar
                    const img = document.getElementById('modal-image');
                    if (data.foto_komplek) {
                        img.src = `/storage/${data.foto_komplek}`;
                    } else {
                        img.src = "{{ asset('img/placeholder-komplek.jpg') }}";
                    }

                    // Tampilkan konten
                    loadingIndicator.classList.add('hidden');
                    content.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modal-nama-komplek').textContent = 'Error';
                    document.getElementById('modal-body').innerHTML =
                        `<div class="text-center py-16"><p class="text-red-500 font-semibold">Gagal memuat data.</p><p class="text-sm text-gray-500 mt-2">Silakan coba lagi.</p></div>`;
                });
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Escape key to close modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Loading state for filter form
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.filter-form');
            const submitBtn = document.querySelector('.btn-filter-apply');

            if (form && submitBtn) {
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
            }
        });
    </script>
@endsection
