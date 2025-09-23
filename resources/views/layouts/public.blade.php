<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
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
    <nav class="navbar-blur shadow-lg sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex items-center justify-between py-4">

            <!-- Logo Dinas -->
            <div class="flex items-center gap-3 animate-fade-in">
                <div class="relative">
                    <img src="{{ asset('img/logo-pemkot.png') }}" alt="logo pemkot"
                        class="w-10 h-auto object-contain transition-transform duration-300 hover:scale-110">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-sigap-yellow to-orange-500 rounded-full opacity-0 hover:opacity-20 transition-opacity duration-300">
                    </div>
                </div>
                <span
                    class="font-semibold text-sm leading-tight text-gray-800 hover:text-sigap-yellow transition-colors duration-300">
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
                <a href="{{ route('regulasi') }}" class="nav-link {{ request()->routeIs('regulasi*') ? 'active' : '' }}">
                    Regulasi
                </a>
                <a href="{{ route('kontak') }}" class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}">
                    Kontak
                </a>
            </div>

            <!-- Login Button / User Dropdown Desktop -->
            <div class="hidden md:flex items-center gap-4">
                @auth
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
                                <a href="{{ route('profil.index') }}#profil" class="flex items-center gap-3 rounded-md px-2 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 hover:text-sigap-yellow">
                                    <i data-feather="user" class="w-5 h-5"></i>
                                    <span>Profil Saya</span>
                                </a>
                                <a href="{{ route('eproposal') }}" class="flex items-center gap-3 rounded-md px-2 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 hover:text-sigap-yellow">
                                    <i data-feather="file-text" class="w-5 h-5"></i>
                                    <span>Proposal Saya</span>
                                </a>
                                <a href="{{ route('pengaduan') }}" class="flex items-center gap-3 rounded-md px-2 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 hover:text-sigap-yellow">
                                    <i data-feather="message-square" class="w-5 h-5"></i>
                                    <span>Pengaduan Saya</span>
                                </a>

                                <div class="my-1 h-px bg-gray-200"></div>

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
                @else
                    <!-- Login Button -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="btn-gradient">
                            <span class="relative z-10">Masuk</span>
                        </a>
                    </div>
                @endauth
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

                        <a href="#" target="_blank" rel="noopener noreferrer"
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

                        <a href="#" target="_blank" rel="noopener noreferrer"
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
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('informasi-fasum') }}">Informasi FASUM</a></li>
                        <li><a href="{{ route('eproposal') }}">E-Proposal PSU</a></li>
                        <li><a href="{{ route('pengaduan') }}">Pengaduan</a></li>
                        <li><a href="{{ route('regulasi') }}">Regulasi</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
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

    <script>
        feather.replace()
    </script>
</body>

</html>