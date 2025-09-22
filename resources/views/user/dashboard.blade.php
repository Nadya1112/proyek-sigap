@extends('layouts.public')

@section('title', 'Dashboard Pengguna')

@section('content')

{{-- ====================================================================== --}}
{{-- HERO HEADER                                                            --}}
{{-- ====================================================================== --}}
<header class="relative overflow-hidden" style="background: linear-gradient(135deg, #F59E0B 0%, #F97316 50%, #EA580C 100%);">
    {{-- Latar belakang pola SVG subtle --}}
    <div class="absolute inset-0 pointer-events-none" style="background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,1000 1000,0 1000,1000"/></svg></div>

    <div class="container mx-auto px-6 py-12 md:py-16 relative">
        <div class="max-w-4xl mx-auto text-center text-white">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-white/15 ring-1 ring-white/25 backdrop-blur-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
                Dashboard Pengguna
            </div>

            {{-- Judul Utama --}}
            <h1 class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight drop-shadow-[0_2px_3px_rgba(0,0,0,0.2)]">
                Selamat Datang, {{ $akun['name'] ?? 'Pengguna' }}
            </h1>
            <p class="mt-3 text-lg text-white/90 max-w-2xl mx-auto">
                Kelola semua pengaduan dan e-proposal Anda dengan mudah dalam satu tempat terpusat.
            </p>

            {{-- Quick Stats (Rapi dengan 3 item) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-10 max-w-3xl mx-auto">
                <div class="rounded-2xl bg-white/10 p-4 text-center ring-1 ring-white/20 backdrop-blur-sm transition hover:bg-white/15">
                    <div class="text-3xl font-bold">{{ $pengaduan['stats']['total'] ?? 0 }}</div>
                    <div class="mt-1 text-xs uppercase tracking-wider text-white/80">Total Pengaduan</div>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 text-center ring-1 ring-white/20 backdrop-blur-sm transition hover:bg-white/15">
                    <div class="text-3xl font-bold">{{ $proposal['stats']['total'] ?? 0 }}</div>
                    <div class="mt-1 text-xs uppercase tracking-wider text-white/80">Total Proposal</div>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 text-center ring-1 ring-white/20 backdrop-blur-sm transition hover:bg-white/15">
                     <div class="text-3xl font-bold">{{ $pengaduan['stats']['proses'] ?? 0 }}</div>
                    <div class="mt-1 text-xs uppercase tracking-wider text-white/80">Dalam Proses</div>
                </div>
            </div>
        </div>
    </div>
</header>


{{-- ====================================================================== --}}
{{-- KONTEN UTAMA (Layout Vertikal & Desain Baru)                           --}}
{{-- ====================================================================== --}}
<main class="bg-gray-50 py-10 lg:py-16">
    <div class="container mx-auto px-6">
        {{-- Wrapper untuk layout vertikal dengan spacing --}}
        <div class="max-w-4xl mx-auto space-y-8">

            <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-3">
                    <svg class="w-6 h-6 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                    Menu Aksi Cepat
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pilih salah satu menu di bawah ini untuk memulai.</p>
                <div class="mt-5 space-y-4">
                    <a href="{{ route('pengaduan') }}" class="group flex items-center gap-5 p-4 rounded-xl bg-gray-50 border-2 border-transparent hover:border-orange-500 hover:bg-orange-50 transition-all duration-300">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-orange-100 text-orange-600 grid place-content-center transition-all duration-300 group-hover:scale-110">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-2.236 9.168-5.514M15 11.236h3.957M5.436 13.683v-2.157c0-1.153.29-2.26.831-3.25" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-base">Buat Pengaduan Baru</h3>
                            <p class="text-sm text-gray-500 mt-1">Laporkan kerusakan atau masalah fasilitas umum di sekitar Anda.</p>
                        </div>
                    </a>
                    <a href="{{ route('eproposal') }}" class="group flex items-center gap-5 p-4 rounded-xl bg-gray-50 border-2 border-transparent hover:border-orange-500 hover:bg-orange-50 transition-all duration-300">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-orange-100 text-orange-600 grid place-content-center transition-all duration-300 group-hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-base">Ajukan E-Proposal</h3>
                            <p class="text-sm text-gray-500 mt-1">Ajukan proposal untuk bantuan prasarana, sarana, dan utilitas umum.</p>
                        </div>
                    </a>

                     <a href="{{ route('profil.index') }}#profil" class="group flex items-center gap-5 p-4 rounded-xl bg-gray-50 border-2 border-transparent hover:border-gray-400 hover:bg-gray-100 transition-all duration-300">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gray-200 text-gray-600 grid place-content-center transition-all duration-300 group-hover:scale-110">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-base">Kelola Profil Anda</h3>
                            <p class="text-sm text-gray-500 mt-1">Perbarui informasi, kontak, dan kata sandi akun Anda.</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Daftar Pengaduan Saya</h2>
                        <p class="text-sm text-gray-500 mt-1">Berikut adalah 5 pengaduan terakhir yang Anda buat.</p>
                    </div>
                     <a href="{{ route('pengaduan') }}" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-white text-sm font-semibold bg-gradient-to-r from-[#FFA72B] to-[#F16A00] hover:opacity-90 transition whitespace-nowrap">
                        Lihat Semua
                    </a>
                </div>
                <div class="mt-4 border-t border-gray-100">
                     @forelse($pengaduan['recent'] as $row)
                        <div class="py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $row->judul }}</p>
                                <p class="text-xs text-gray-500 mt-1">Dibuat pada: {{ \Carbon\Carbon::parse($row->created_at)->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full flex-shrink-0 {{
                                $row->status==='selesai' ? 'bg-green-100 text-green-800' :
                                ($row->status==='draft' ? 'bg-gray-100 text-gray-800' : 'bg-orange-100 text-orange-800')
                            }}">{{ ucfirst($row->status) }}</span>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-sm text-gray-500">Anda belum pernah membuat pengaduan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

             <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
                 <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Daftar E-Proposal Saya</h2>
                        <p class="text-sm text-gray-500 mt-1">Berikut adalah 5 proposal terakhir yang Anda ajukan.</p>
                    </div>
                     <a href="{{ route('eproposal') }}" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-white text-sm font-semibold bg-gradient-to-r from-[#FFA72B] to-[#F16A00] hover:opacity-90 transition whitespace-nowrap">
                        Lihat Semua
                    </a>
                </div>
                 <div class="mt-4 border-t border-gray-100">
                     @forelse($proposal['recent'] as $row)
                        <div class="py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $row->judul }}</p>
                                <p class="text-xs text-gray-500 mt-1">Diajukan pada: {{ \Carbon\Carbon::parse($row->created_at)->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full flex-shrink-0 {{
                                $row->status==='disetujui' ? 'bg-green-100 text-green-800' :
                                ($row->status==='ditolak' ? 'bg-red-100 text-red-800' :
                                ($row->status==='draft' ? 'bg-gray-100 text-gray-800' : 'bg-orange-100 text-orange-800'))
                            }}">{{ ucfirst($row->status) }}</span>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-sm text-gray-500">Anda belum pernah mengajukan proposal.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- <div class="text-center">
                 <p class="text-sm text-gray-500">Sesi Anda akan berakhir secara otomatis. Klik untuk keluar sekarang.</p>
                 <div class="mt-4 flex items-center justify-center">
                     <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="font-semibold text-sm text-red-600 hover:underline">
                         Keluar dari Akun
                     </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                      </form>
                 </div>
            </div> -->

        </div>
    </div>
</main>
@endsection