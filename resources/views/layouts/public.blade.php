<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIGAP - KOMPLEK')</title>
    
    @vite(['resources/css/app.css','resources/js/app.js'])

    <!-- Alpine.js for dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Enhanced CSS -->
    <link rel="stylesheet" href="{{ asset('css/enhanced-ui.css') }}">

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>

<body class="bg-gray-50">

    <!-- 🔹 NAVBAR -->
    <nav class="navbar-blur shadow-lg sticky top-0 z-50 transition-all duration-300" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex items-center justify-between py-4">

                <!-- Logo Dinas -->
                <div class="flex items-center gap-2 md:gap-3 animate-fade-in flex-1 md:flex-initial">
                    <div class="relative flex-shrink-0">
                        <img src="{{ asset('img/logo-pemkot.png') }}" alt="logo pemkot"
                            class="w-8 md:w-10 h-auto object-contain transition-transform duration-300 hover:scale-110">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-sigap-yellow to-orange-500 rounded-full opacity-0 hover:opacity-20 transition-opacity duration-300">
                        </div>
                    </div>
                    <span
                        class="font-semibold text-xs md:text-sm leading-tight text-gray-800 hover:text-sigap-yellow transition-colors duration-300 line-clamp-2">
                        Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin
                    </span>
                </div>

                <!-- Menu Desktop -->
                <div id="desktop-menu" style="display: none;">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('home') }}#fitur" class="nav-link">
                        Fitur
                    </a>
                    <a href="{{ route('sebaran') }}" class="nav-link {{ request()->routeIs('sebaran') ? 'active' : '' }}">
                        Sebaran Komplek
                    </a>
                    <a href="{{ route('regulasi') }}" class="nav-link {{ request()->routeIs('regulasi*') ? 'active' : '' }}">
                        Regulasi
                    </a>
                    <a href="{{ route('home') }}#kontak" class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}">
                        Kontak
                    </a>
                </div>

                <!-- Login Button / User Dropdown Desktop -->
                <div id="desktop-user-section" style="display: none;">
                    @auth
                        <div class="flex items-center gap-4">
                             <!-- Notif Icon -->
                            <a href="{{ route('user.notifications') }}" class="relative text-gray-600 hover:text-sigap-yellow transition-colors duration-300">
                                <i data-feather="bell" class="w-6 h-6"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                                @endif
                            </a>
                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="flex items-center gap-3 text-gray-700 hover:text-sigap-yellow transition-colors duration-300 focus:outline-none">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-sigap-yellow to-sigap-orange rounded-full flex items-center justify-center transform transition-transform duration-300 hover:scale-110">
                                        <span class="text-white font-bold text-base">
                                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                        </span>
                                    </div>
                                    <span class="font-semibold">{{ auth()->user()->name }}</span>
                                    <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': open }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 transform scale-100"
                                    x-transition:leave-end="opacity-0 transform scale-95"
                                    class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-lg border border-gray-100 z-50">

                                    <!-- User Info -->
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-14 h-14 bg-gradient-to-br from-sigap-yellow to-sigap-orange rounded-full flex items-center justify-center">
                                                <span class="text-white font-bold text-xl">
                                                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 text-lg">{{ auth()->user()->name }}</p>
                                                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-1 p-2">
                                    <a href="{{ route('user.dashboard') }}"
                                        class="flex items-center gap-3 rounded-md px-2 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:text-sigap-yellow {{ request()->routeIs('user.dashboard') ? 'bg-gray-100 text-sigap-yellow' : 'hover:bg-gray-50' }}">
                                        <i data-feather="grid" class="w-5 h-5"></i>
                                        <span>Dashboard</span>
                                    </a>
                                    <a href="{{ route('user.notifications') }}"
                                        class="flex items-center justify-between gap-3 rounded-md px-2 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:text-sigap-yellow {{ request()->routeIs('user.notifications') ? 'bg-gray-100 text-sigap-yellow' : 'hover:bg-gray-50' }}">
                                        <div class="flex items-center gap-3">
                                            <i data-feather="bell" class="w-5 h-5"></i>
                                            <span>Notifikasi</span>
                                        </div>
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <span class="text-xs font-bold bg-red-500 text-white rounded-full px-2 py-0.5">{{ auth()->user()->unreadNotifications->count() }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('profil.index') }}#profil" class="flex items-center gap-3 rounded-md px-2 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 hover:text-sigap-yellow">
                                        <i data-feather="user" class="w-5 h-5"></i>
                                        <span>Profil Saya</span>
                                    </a>
                                    <a href="{{ route('user.dashboard') }}#daftar-proposal" class="flex items-center gap-3 rounded-md px-2 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 hover:text-orange-500">
                                        <i data-feather="file-text" class="w-5 h-5"></i>
                                        <span>Proposal Saya</span>
                                    </a>
                                    <a href="{{ route('user.dashboard') }}#daftar-pengaduan" class="flex items-center gap-3 rounded-md px-2 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 hover:text-orange-500">
                                        <i data-feather="message-square" class="w-5 h-5"></i>
                                        <span>Pengaduan Saya</span>
                                    </a>
                                    <!-- <a href="{{ route('user.dashboard') }}#daftar-komplek-baru" class="flex items-center gap-3 rounded-md px-2 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 hover:text-orange-500">
                                        <i data-feather="home" class="w-5 h-5"></i>
                                        <span>Ajuan Komplek Baru</span>
                                    </a>
                                    <div class="my-1 h-px bg-gray-200"></div> -->

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-3 rounded-md px-2 py-1.5 text-sm font-medium text-red-600 transition-colors hover:bg-red-50">
                                            <i data-feather="log-out" class="w-5 h-5"></i>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Login Button -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}" class="btn-gradient">
                                <span class="relative z-10">Masuk</span>
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile Menu Button & User Icon -->
                <div id="mobile-controls" style="display: none;" class="flex items-center gap-4">
                    @auth
                        <div class="flex items-center">
                            <!-- Mobile User Button -->
                            <a href="{{ route('user.dashboard') }}"
                                class="w-9 h-9 bg-gradient-to-br from-sigap-yellow to-sigap-orange rounded-full flex items-center justify-center transform transition-transform duration-300 hover:scale-110">
                                <span class="text-white font-bold text-sm">
                                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                </span>
                            </a>
                        </div>
                    @else
                        <!-- Mobile Login Button -->
                        <a href="{{ route('login') }}" class="btn-gradient text-sm px-4 py-2">
                            <span class="relative z-10">Masuk</span>
                        </a>
                    @endauth

                    <!-- Hamburger Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="text-gray-600 hover:text-sigap-yellow focus:outline-none transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4"
                id="mobile-menu-container"
                style="display: none;">
                <div class="flex flex-col space-y-2 pt-4 pb-4 border-t border-gray-200 mt-4">
                    <a href="{{ route('home') }}" 
                        class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        @click="mobileMenuOpen = false">
                        <i data-feather="home" class="w-5 h-5"></i>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('home') }}#fitur" 
                        class="mobile-nav-link"
                        @click="mobileMenuOpen = false">
                        <i data-feather="grid" class="w-5 h-5"></i>
                        <span>Fitur</span>
                    </a>
                    <a href="{{ route('sebaran') }}" 
                        class="mobile-nav-link {{ request()->routeIs('sebaran') ? 'active' : '' }}"
                        @click="mobileMenuOpen = false">
                        <i data-feather="map-pin" class="w-5 h-5"></i>
                        <span>Sebaran Komplek</span>
                    </a>
                    <a href="{{ route('regulasi') }}" 
                        class="mobile-nav-link {{ request()->routeIs('regulasi*') ? 'active' : '' }}"
                        @click="mobileMenuOpen = false">
                        <i data-feather="book" class="w-5 h-5"></i>
                        <span>Regulasi</span>
                    </a>
                    <a href="{{ route('home') }}#kontak" 
                        class="mobile-nav-link"
                        @click="mobileMenuOpen = false">
                        <i data-feather="phone" class="w-5 h-5"></i>
                        <span>Kontak</span>
                    </a>

                    @auth
                        <div class="border-t border-gray-200 pt-3 mt-2">
                            <div class="px-4 py-2 mb-2">
                                <p class="font-semibold text-gray-800 text-sm">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('user.dashboard') }}" 
                                class="mobile-nav-link"
                                @click="mobileMenuOpen = false">
                                <i data-feather="grid" class="w-5 h-5"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('user.notifications') }}"
                                class="mobile-nav-link {{ request()->routeIs('user.notifications') ? 'active' : '' }}"
                                @click="mobileMenuOpen = false">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center gap-4">
                                        <i data-feather="bell" class="w-5 h-5"></i>
                                        <span>Notifikasi</span>
                                    </div>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="text-xs font-bold bg-red-500 text-white rounded-full px-2 py-0.5">{{ auth()->user()->unreadNotifications->count() }}</span>
                                    @endif
                                </div>
                            </a>
                            <a href="{{ route('profil.index') }}#profil" 
                                class="mobile-nav-link"
                                @click="mobileMenuOpen = false">
                                <i data-feather="user" class="w-5 h-5"></i>
                                <span>Profil Saya</span>
                            </a>
                            <a href="{{ route('user.dashboard') }}#daftar-proposal" 
                                class="mobile-nav-link"
                                @click="mobileMenuOpen = false">
                                <i data-feather="file-text" class="w-5 h-5"></i>
                                <span>Proposal Saya</span>
                            </a>
                            <a href="{{ route('user.dashboard') }}#daftar-pengaduan" 
                                class="mobile-nav-link"
                                @click="mobileMenuOpen = false">
                                <i data-feather="message-square" class="w-5 h-5"></i>
                                <span>Pengaduan Saya</span>
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="mobile-nav-link w-full text-left text-red-600 hover:bg-red-50">
                                    <i data-feather="log-out" class="w-5 h-5"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
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
                        Sinergi Gerak Aksi PSU Komplek Perumahan - Melayani dengan sepenuh hati untuk kemajuan
                        infrastruktur perumahan Kota Banjarmasin.
                    </p>

                    <div class="flex gap-4">

                        <a href="https://www.facebook.com/people/Disperkim-Banjarmasin/pfbid0Jqdvpqbdw734LNHiWroe4cvWyFYNddscASTYtG8yEcVnJPZhSZyVJZtW6Z5ZW5Tyl/" target="_blank" rel="noopener noreferrer"
                            class="social-btn social-btn-blue">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>

                        <a href="https://www.instagram.com/disperkim.banjarmasin" target="_blank"
                            rel="noopener noreferrer" class="social-btn social-btn-pink">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>

                        <a href="https://youtube.com/@perumahanbanjarmasin4471?si=ch_ellCkPhhentJJ" target="_blank" rel="noopener noreferrer"
                            class="social-btn social-btn-red">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M19.802 7.022a2.433 2.433 0 00-1.72-1.72C16.68 5 12 5 12 5s-4.68 0-6.082.302a2.433 2.433 0 00-1.72 1.72C4 8.423 4 12 4 12s0 3.577.198 4.978a2.433 2.433 0 001.72 1.72C7.32 19 12 19 12 19s4.68 0 6.082-.302a2.433 2.433 0 001.72-1.72C20 15.577 20 12 20 12s0-3.577-.198-4.978zM9.545 14.568V9.432L14.455 12l-4.91 2.568z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>

                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-bold mb-6">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="footer-link">Beranda</a></li>
                        <li><a href="{{ route('informasi-fasum') }}" class="footer-link">Informasi FASUM</a></li>
                        <li><a href="{{ route('eproposal') }}" class="footer-link">E-Proposal PSU</a></li>
                        <li><a href="{{ route('pengaduan') }}" class="footer-link">Pengaduan</a></li>
                        <li><a href="{{ route('regulasi') }}" class="footer-link">Regulasi</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div id="kontak">
                    <h4 class="text-lg font-bold mb-6">Kontak Kami</h4>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-gray-400">
                            <div class="contact-icon">
                                <svg class="w-4 h-4 text-sigap-yellow" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span>Jl. RE Martadinata No.1, Banjarmasin</span>
                        </div>

                        <a href="https://wa.me/6281935288889" target="_blank" rel="noopener noreferrer"
                            class="flex items-center gap-3 text-gray-400 hover:text-sigap-yellow transition-colors duration-300">
                            <div class="contact-icon">
                                <svg class="w-4 h-4 text-sigap-yellow" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <span>+62 819-3528-8889 (WA)</span>
                        </a>

                        <a href="mailto:dckp.bjm@gmail.com"
                            class="flex items-center gap-3 text-gray-400 hover:text-sigap-yellow transition-colors duration-300">
                            <div class="contact-icon">
                                <svg class="w-4 h-4 text-sigap-yellow" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span>dckp.bjm@gmail.com</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-12 pt-8 border-t border-gray-800 text-center">
                <p class="text-gray-400">
                    © {{ date('Y') }} SIGAP KOMPLEK - Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin.
                    <span class="text-sigap-yellow">All rights reserved.</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Enhanced JavaScript -->
    <script src="{{ asset('js/enhanced-ui.js') }}"></script>

    <!-- Tombol WhatsApp Statis -->
    <a href="https://wa.me/6281935288889?text=Halo%20SIGAP%20Komplek,%20saya%20ingin%20bertanya."
        target="_blank" rel="noopener noreferrer"
        class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg hover:bg-green-600 transition-transform duration-300 transform hover:scale-110 z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
            <path
                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01s-.521.074-.792.372c-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
        </svg>
    </a>

    <script>
        feather.replace()
        
        // Responsive Navbar Handler
        function handleNavbarResponsive() {
            const desktopMenu = document.getElementById('desktop-menu');
            const desktopUserSection = document.getElementById('desktop-user-section');
            const mobileControls = document.getElementById('mobile-controls');
            const mobileMenuContainer = document.getElementById('mobile-menu-container');
            
            if (window.innerWidth >= 1024) {
                // Desktop view
                if (desktopMenu) desktopMenu.style.display = 'flex';
                if (desktopUserSection) desktopUserSection.style.display = 'flex';
                if (mobileControls) mobileControls.style.display = 'none';
                if (mobileMenuContainer) mobileMenuContainer.style.display = 'none';
            } else {
                // Mobile/Tablet view
                if (desktopMenu) desktopMenu.style.display = 'none';
                if (desktopUserSection) desktopUserSection.style.display = 'none';
                if (mobileControls) mobileControls.style.display = 'flex';
                // Mobile menu container akan di-handle oleh Alpine.js x-show
            }
        }
        
        // Run on load
        handleNavbarResponsive();
        
        // Run on resize
        window.addEventListener('resize', handleNavbarResponsive);
    </script>
</body>

</html>