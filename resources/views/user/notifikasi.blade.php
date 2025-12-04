@extends('layouts.public')

@section('title', 'Notifikasi Saya')

@section('content')
<header class="relative overflow-hidden" style="background: linear-gradient(135deg, #F59E0B 0%, #F97316 50%, #EA580C 100%);">
    <div class="absolute inset-0 pointer-events-none" style="background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,1000 1000,0 1000,1000"/></svg></div>
    <div class="container mx-auto px-6 py-12 md:py-16 relative">
        <div class="max-w-4xl mx-auto text-center text-white">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-white/15 ring-1 ring-white/25 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Notifikasi
            </div>
            <h1 class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight drop-shadow-[0_2px_3px_rgba(0,0,0,0.2)]">
                Riwayat Notifikasi Anda
            </h1>
            <p class="mt-3 text-lg text-white/90 max-w-2xl mx-auto">
                Semua pembaruan terkait pengaduan dan e-proposal Anda akan muncul di sini.
            </p>
        </div>
    </div>
</header>

<main class="bg-gray-50 py-10 lg:py-16">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
                <div class="space-y-4">
                    @forelse ($notifications as $notification)
                        <div class="p-4 rounded-lg flex items-start gap-4 {{ $notification->read_at ? 'bg-gray-50' : 'bg-orange-50 border border-orange-200' }}">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-orange-100 text-orange-600 grid place-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-grow">
                                <p class="text-sm text-gray-700 font-semibold">{{ $notification->data['title'] ?? 'Pemberitahuan' }}</p>
                                <p class="text-sm text-gray-600">{!! $notification->data['body'] ?? ($notification->data['message'] ?? 'Detail notifikasi tidak tersedia.') !!}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if (!$notification->read_at)
                                <div class="flex-shrink-0">
                                    <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada notifikasi</h3>
                            <p class="mt-1 text-sm text-gray-500">Saat ini Anda belum memiliki notifikasi baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
             <div class="mt-8 text-center">
                <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-gray-600 text-sm font-semibold hover:bg-gray-200 hover:text-gray-800 transition">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</main>
@endsection
