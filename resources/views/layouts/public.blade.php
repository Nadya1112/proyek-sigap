<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'SIGAP - KOMPLEK')</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    
    <!-- Enhanced CSS -->
    <link rel="stylesheet" href="{{ asset('css/enhanced-ui.css') }}">
</head>

<body class="bg-gray-50">

    <!-- 🔹 NAVBAR -->
    <nav class="navbar-blur shadow-lg sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex items-center justify-between py-4">

            <!-- Logo Dinas -->
            <div class="flex items-center gap-3 animate-fade-in">
                <div class="relative">
                    <img src="{{ asset('img/logo-pemkot.png') }}" alt="logo pemkot" 
                         class="w-10 h-auto object-contain transition-transform duration-300 hover:scale-110">
                    <div class="absolute -inset-1 bg-gradient-to-r from-sigap-yellow to-orange-500 rounded-full opacity-0 hover:opacity-20 transition-opacity duration-300"></div>
                </div>
                <span class="font-semibold text-sm leading-tight text-gray-800 hover:text-sigap-yellow transition-colors duration-300">
                    Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin
                </span>
            </div>

            <!-- Menu Desktop -->
            <div class="hidden md:flex gap-8 text-gray-600 font-medium">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('home') }}#fitur" class="nav-link">
                    Fitur
                </a>
                <a href="{{ route('sebaran') }}" class="nav-link {{ request()->routeIs('sebaran') ? 'active' : '' }}">
                    Sebaran Komplek
                </a>
                <a href="{{ route('informasi') }}" class="nav-link {{ request()->routeIs('informasi') ? 'active' : '' }}">
                    Informasi
                </a>
                <a href="{{ route('kontak') }}" class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}">
                    Kontak
                </a>
            </div>
    </nav>

    <!-- 🔹 KONTEN HALAMAN -->
    @yield('content')

    <!-- 🔹 FOOTER -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <!-- Logo & Description -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('img/logo-sigap.png') }}" alt="logo" class="w-10 h-10">
                        <span class="text-2xl font-bold text-gradient">SIGAP KOMPLEK</span>
                    </div>
                    
                    <p class="text-gray-400 leading-relaxed mb-6 max-w-md">
                        Sinergi Gerak Aksi PSU Komplek Perumahan - Melayani dengan sepenuh hati untuk kemajuan infrastruktur perumahan Kota Banjarmasin.
                    </p>
                    
                    <!-- Social Media -->
                    <div class="flex gap-4">
                        <a href="#" class="social-btn social-btn-yellow">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-btn social-btn-blue">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-btn social-btn-pink">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-bold mb-6">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="footer-link">Beranda</a></li>
                        <li><a href="{{ route('informasi') }}" class="footer-link">Informasi FASUM</a></li>
                        <li><a href="{{ route('eproposal') }}" class="footer-link">E-Proposal PSU</a></li>
                        <li><a href="{{ route('pengaduan') }}" class="footer-link">Pengaduan</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-bold mb-6">Kontak Kami</h4>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-gray-400">
                            <div class="contact-icon">
                                <svg class="w-4 h-4 text-sigap-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span>Jl. RE Martadinata No.1, Banjarmasin</span>
                        </div>
                        
                        <div class="flex items-center gap-3 text-gray-400">
                            <div class="contact-icon">
                                <svg class="w-4 h-4 text-sigap-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <span>(0511) 123-4567</span>
                        </div>
                        
                        <div class="flex items-center gap-3 text-gray-400">
                            <div class="contact-icon">
                                <svg class="w-4 h-4 text-sigap-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span>sigapkomplek.go.id</span>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-lg font-bold mb-6">Layanan</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="footer-link">Pengelolaan FASUM</a></li>
                        <li><a href="#" class="footer-link">Bantuan PSU</a></li>
                        <li><a href="#" class="footer-link">Konsultasi Teknis</a></li>
                        <li><a href="#" class="footer-link">Monitoring Proyek</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-12 pt-8 border-t border-gray-800 text-center">
                <p class="text-gray-400">
                    © 2025 SIGAP KOMPLEK - Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin. 
                    <span class="text-sigap-yellow">All rights reserved.</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Enhanced JavaScript -->
    <script src="{{ asset('js/enhanced-ui.js') }}"></script>
</body>

</html>