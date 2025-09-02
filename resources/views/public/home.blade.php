@extends('layouts.public')

@section('title', 'Beranda - SIGAP KOMPLEK')

@section('content')
    <!-- 🔹 HEADER HERO -->
    <header class="relative h-screen bg-cover bg-center parallax overflow-hidden"
        style="background-image: url('{{ asset('img/perumahan.jpg') }}');">
        <!-- Animated background overlay -->
        <div class="hero-overlay"></div>

        <!-- Floating elements -->
        <div class="floating-elements"></div>

        <!-- Animated particles -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="particle particle-1"></div>
            <div class="particle particle-2"></div>
            <div class="particle particle-3"></div>
        </div>

        <div
            class="relative z-10 container mx-auto h-full flex flex-col md:flex-row items-center justify-center md:justify-between px-6">

            <!-- Text Content -->
            <div class="text-white max-w-2xl animate-slide-up">
                <p class="mb-4 text-lg opacity-90 animate-fade-in text-shadow" style="animation-delay: 0.2s;">Selamat Datang
                    di</p>

                <h1 class="text-5xl md:text-7xl font-bold mb-4 leading-tight animate-fade-in text-shadow"
                    style="animation-delay: 0.4s;">
                    <span class="block">SIGAP</span>
                    <span class="hero-title-gradient text-6xl md:text-8xl">KOMPLEK</span>
                </h1>

                <p class="italic text-xl mb-6 opacity-90 animate-fade-in text-shadow" style="animation-delay: 0.6s;">
                    ( Sinergi Gerak Aksi PSU Komplek Perumahan )
                </p>

                <p class="text-lg leading-relaxed mb-8 opacity-90 animate-fade-in max-w-lg text-shadow"
                    style="animation-delay: 0.8s;">
                    Melayani Penanganan PSU (Prasarana, Sarana, dan Utilitas Umum) Perumahan Kota Banjarmasin dengan
                    teknologi terdepan dan pelayanan terbaik.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 animate-fade-in" style="animation-delay: 1s;">
                    <a href="#fitur" class="cta-primary">
                        <span class="relative z-10">Jelajahi Fitur</span>
                        <div class="cta-primary-hover"></div>
                    </a>

                    <a href="{{ route('kontak') }}" class="cta-secondary">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>

            <!-- Logo Section -->
            <div class="mt-12 md:mt-0 flex justify-center md:justify-end animate-bounce-in" style="animation-delay: 1.2s;">
                <div class="hero-logo-container">
                    <!-- Glowing background -->
                    <div class="hero-logo-glow"></div>

                    <!-- Main logo container -->
                    <div class="hero-logo-main">
                        <img src="{{ asset('img/logo-sigap.png') }}" alt="logo sigap"
                            class="w-48 md:w-64 h-auto object-contain filter drop-shadow-lg">
                    </div>

                    <!-- Decorative rings -->
                    <div class="hero-logo-ring-1"></div>
                    <div class="hero-logo-ring-2"></div>
                </div>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="scroll-indicator">
            <div class="scroll-indicator-container">
                <div class="scroll-indicator-dot"></div>
            </div>
        </div>
    </header>

    <!-- 🔹 FITUR SECTION -->
    <section id="fitur" class="relative py-24 bg-gradient-to-b from-gray-50 to-white overflow-hidden">
        <!-- Background decoration -->
        <div class="section-bg-decoration">
            <div class="decoration-1"></div>
            <div class="decoration-2"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Fitur <span class="text-gradient">Unggulan</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Akses mudah dan cepat ke berbagai layanan PSU untuk kemudahan masyarakat
                </p>
                <div class="section-divider"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Card 1 - Informasi FASUM -->
                <div class="feature-card-wrapper group">
                    <div class="feature-card">
                        <!-- Background pattern -->
                        <div class="feature-card-bg feature-card-bg-yellow"></div>

                        <!-- Icon -->
                        <div class="relative z-10">
                            <div class="feature-icon icon-yellow">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="feature-card-title">
                                Informasi FASUM
                            </h3>

                            <p class="feature-card-desc">
                                Data tabel sertifikat FASUM yang telah & belum diserahkan dengan sistem monitoring
                                real-time.
                            </p>

                            <a href="{{ route('informasi') }}" class="feature-card-btn">
                                Lihat Detail
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2 - E-Proposal PSU -->
                <div class="feature-card-wrapper group">
                    <div class="feature-card">
                        <!-- Background pattern -->
                        <div class="feature-card-bg feature-card-bg-blue"></div>

                        <!-- Icon -->
                        <div class="relative z-10">
                            <div class="feature-icon icon-blue">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="feature-card-title">
                                E-Proposal PSU
                            </h3>

                            <p class="feature-card-desc">
                                Platform digital untuk pengajuan proposal bantuan PSU dengan proses yang transparan dan
                                efisien.
                            </p>

                            <a href="{{ route('eproposal') }}" class="feature-card-btn">
                                Ajukan Proposal
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 3 - Pengaduan Masyarakat -->
                <div class="feature-card-wrapper group">
                    <div class="feature-card">
                        <!-- Background pattern -->
                        <div class="feature-card-bg feature-card-bg-green"></div>

                        <!-- Icon -->
                        <div class="relative z-10">
                            <div class="feature-icon icon-green">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="feature-card-title">
                                Pengaduan Masyarakat
                            </h3>

                            <p class="feature-card-desc">
                                Layanan pengaduan masyarakat yang responsif dengan sistem tracking dan follow-up otomatis.
                            </p>

                            <a href="{{ route('pengaduan') }}" class="feature-card-btn">
                                Buat Pengaduan
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Stats Section -->
            <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8 animate-fade-in">
                <div class="stats-item group">
                    <div class="stats-number stats-yellow">
                        <span class="counter" data-target="500">0</span>
                    </div>
                    <p class="stats-label">Komplek Terdaftar</p>
                </div>

                <div class="stats-item group">
                    <div class="stats-number stats-blue">
                        <span class="counter" data-target="100">0</span>
                    </div>
                    <p class="stats-label">Proposal Diproses</p>
                </div>

                <div class="stats-item group">
                    <div class="stats-number stats-green">
                        24/7
                    </div>
                    <p class="stats-label">Layanan Online</p>
                </div>

                <div class="stats-item group">
                    <div class="stats-number stats-red">
                        <span class="counter" data-target="98">0</span>
                    </div>
                    <p class="stats-label">Kepuasan Pengguna</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 🔹 ABOUT SECTION -->
    <section class="about-section">
        <!-- Animated background -->
        <div class="about-bg">
            <div class="about-bg-gradient"></div>
            <div class="about-bg-particles">
                <div class="about-particle about-particle-1"></div>
                <div class="about-particle about-particle-2"></div>
                <div class="about-particle about-particle-3"></div>
            </div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <!-- Text Content -->
                <div class="text-white animate-slide-up">
                    <h2 class="text-4xl md:text-5xl font-bold mb-6 text-shadow">
                        Tentang <span class="text-gradient">SIGAP KOMPLEK</span>
                    </h2>

                    <div class="space-y-6 text-lg leading-relaxed">
                        <p class="opacity-90 text-shadow">
                            SIGAP KOMPLEK adalah sistem terintegrasi yang dirancang khusus untuk mengelola dan memantau
                            Prasarana, Sarana, dan Utilitas Umum (PSU) di kompleks perumahan Kota Banjarmasin.
                        </p>

                        <p class="opacity-90 text-shadow">
                            Dengan teknologi modern dan antarmuka yang user-friendly, kami berkomitmen memberikan
                            pelayanan terbaik untuk masyarakat dalam pengelolaan infrastruktur perumahan.
                        </p>
                    </div>

                    <!-- Feature highlights -->
                    <div class="mt-8 space-y-4">
                        <div class="about-feature-item group">
                            <div class="about-feature-dot about-feature-dot-yellow"></div>
                            <span class="about-feature-text">Sistem Monitoring Real-time</span>
                        </div>
                        <div class="about-feature-item group">
                            <div class="about-feature-dot about-feature-dot-orange"></div>
                            <span class="about-feature-text">Proses Digital Terintegrasi</span>
                        </div>
                        <div class="about-feature-item group">
                            <div class="about-feature-dot about-feature-dot-light"></div>
                            <span class="about-feature-text">Layanan 24/7 Online</span>
                        </div>
                    </div>
                </div>

                <!-- Interactive Illustration -->
                <div class="relative animate-bounce-in">
                    <div class="dashboard-container">
                        <!-- Mock Dashboard -->
                        <div class="dashboard-content">
                            <div class="dashboard-header">
                                <h4 class="font-bold text-gray-800">Dashboard SIGAP</h4>
                                <div class="dashboard-status">
                                    <div class="status-dot status-green"></div>
                                    <div class="status-dot status-yellow"></div>
                                    <div class="status-dot status-red"></div>
                                </div>
                            </div>

                            <!-- Mock chart -->
                            <div class="dashboard-charts">
                                <div class="chart-item">
                                    <span class="chart-label">FASUM Diserahkan</span>
                                    <div class="chart-bar">
                                        <div class="chart-progress chart-progress-green"></div>
                                    </div>
                                </div>

                                <div class="chart-item">
                                    <span class="chart-label">Proposal Aktif</span>
                                    <div class="chart-bar">
                                        <div class="chart-progress chart-progress-blue"></div>
                                    </div>
                                </div>

                                <div class="chart-item">
                                    <span class="chart-label">Pengaduan Selesai</span>
                                    <div class="chart-bar">
                                        <div class="chart-progress chart-progress-yellow"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating indicators -->
                        <div class="dashboard-indicator dashboard-indicator-success">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>

                        <div class="dashboard-indicator dashboard-indicator-star">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 🔹 BENEFITS SECTION -->
    <section class="benefits-section">
        <!-- Decorative background -->
        <div class="benefits-bg">
            <div class="benefits-decoration-1"></div>
            <div class="benefits-decoration-2"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <!-- Section header -->
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Mengapa Memilih <span class="text-gradient">SIGAP KOMPLEK</span>?
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Solusi terdepan untuk pengelolaan PSU yang efisien, transparan, dan mudah digunakan
                </p>
            </div>

            <!-- Benefits grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Benefit 1 -->
                <div class="benefit-card-wrapper group">
                    <div class="benefit-card">
                        <div class="benefit-card-bg benefit-card-bg-green"></div>

                        <div class="relative z-10">
                            <div class="benefit-icon benefit-icon-green">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>

                            <h3 class="benefit-title benefit-title-green">
                                Proses Cepat
                            </h3>

                            <p class="benefit-desc">
                                Sistem otomatis yang mempercepat proses pengajuan dan persetujuan proposal PSU hingga 70%
                                lebih cepat.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 2 -->
                <div class="benefit-card-wrapper group">
                    <div class="benefit-card">
                        <div class="benefit-card-bg benefit-card-bg-blue"></div>

                        <div class="relative z-10">
                            <div class="benefit-icon benefit-icon-blue">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="benefit-title benefit-title-blue">
                                Keamanan Terjamin
                            </h3>

                            <p class="benefit-desc">
                                Data dan dokumen Anda terlindungi dengan sistem keamanan berlapis dan enkripsi tingkat
                                enterprise.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 3 -->
                <div class="benefit-card-wrapper group">
                    <div class="benefit-card">
                        <div class="benefit-card-bg benefit-card-bg-purple"></div>

                        <div class="relative z-10">
                            <div class="benefit-icon benefit-icon-purple">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="benefit-title benefit-title-purple">
                                Transparansi Penuh
                            </h3>

                            <p class="benefit-desc">
                                Tracking real-time status pengajuan dan progress penanganan PSU untuk transparansi maksimal.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 4 -->
                <div class="benefit-card-wrapper group">
                    <div class="benefit-card">
                        <div class="benefit-card-bg benefit-card-bg-indigo"></div>

                        <div class="relative z-10">
                            <div class="benefit-icon benefit-icon-indigo">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="benefit-title benefit-title-indigo">
                                Mobile Friendly
                            </h3>

                            <p class="benefit-desc">
                                Akses mudah dari berbagai perangkat dengan responsive design yang optimal untuk semua ukuran
                                layar.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 5 -->
                <div class="benefit-card-wrapper group">
                    <div class="benefit-card">
                        <div class="benefit-card-bg benefit-card-bg-red"></div>

                        <div class="relative z-10">
                            <div class="benefit-icon benefit-icon-red">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="benefit-title benefit-title-red">
                                Support 24/7
                            </h3>

                            <p class="benefit-desc">
                                Tim support yang siap membantu Anda kapan saja dengan respon time yang cepat dan solusi yang
                                tepat.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 6 -->
                <div class="benefit-card-wrapper group">
                    <div class="benefit-card">
                        <div class="benefit-card-bg benefit-card-bg-teal"></div>

                        <div class="relative z-10">
                            <div class="benefit-icon benefit-icon-teal">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="benefit-title benefit-title-teal">
                                Analytics & Report
                            </h3>

                            <p class="benefit-desc">
                                Dashboard analytics lengkap dengan laporan real-time untuk monitoring dan evaluasi yang
                                lebih baik.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 🔹 CTA SECTION -->
    <section class="cta-section">
        <!-- Animated background elements -->
        <div class="cta-bg">
            <div class="cta-float-1"></div>
            <div class="cta-float-2"></div>
            <div class="cta-float-3"></div>
        </div>

        <div class="container mx-auto px-6 text-center relative z-10">
            <div class="max-w-4xl mx-auto animate-fade-in">
                <h2 class="text-4xl md:text-6xl font-bold text-white mb-6 text-shadow">
                    Siap Bergabung dengan <br>
                    <span>SIGAP KOMPLEK</span>?
                </h2>

                <p class="text-xl text-white/90 mb-12 max-w-2xl mx-auto leading-relaxed text-shadow">
                    Bergabunglah dengan ribuan pengguna yang sudah merasakan kemudahan layanan PSU digital terdepan
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                    <a href="{{ route('eproposal') }}" class="cta-btn-primary">
                        <span>Mulai Sekarang</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>

                    <a href="{{ route('kontak') }}" class="cta-btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <span>Hubungi Kami</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
