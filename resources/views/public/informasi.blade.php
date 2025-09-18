@extends('layouts.public')

@section('title','Informasi & Dokumen')

@section('content')

{{-- ===== HERO: tanpa kartu statistik ===== --}}
<section class="relative overflow-hidden">
  <div class="relative"
       style="background: linear-gradient(135deg, #F7A623 0%, #FF7A00 45%, #F25C3B 70%, #F04949 100%);">
    <div class="pointer-events-none absolute -top-6 -left-10 w-[360px] h-[360px] opacity-70"
         style="background: radial-gradient(closest-side, rgba(255,179,73,0.55) 0%, rgba(255,179,73,0.26) 34%, rgba(255,179,73,0.10) 60%, transparent 72%); filter: blur(2px);"></div>
    <div class="pointer-events-none absolute -bottom-10 -right-8 w-[340px] h-[340px] opacity-65"
         style="background: radial-gradient(closest-side, rgba(255,120,120,0.45) 0%, rgba(255,120,120,0.22) 35%, rgba(255,120,120,0.10) 58%, transparent 75%); filter: blur(2px);"></div>

    <div class="container mx-auto px-6 py-14 md:py-20 relative text-center text-white">
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 ring-1 ring-white/25">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
        </svg>
        Pusat Informasi
      </span>

      <h1 class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight drop-shadow-[0_1px_2px_rgba(0,0,0,0.10)]">
        Informasi Dokumen Resmi
      </h1>
      <p class="mt-4 text-base md:text-lg text-white/90 max-w-3xl mx-auto">
        Kumpulan peraturan, keputusan, dan dokumen pendukung SIGAP–KOMPLEK. Unduh sesuai kebutuhan Anda.
      </p>
    </div>
  </div>
</section>

{{-- ===== KONTEN: Judul + pencarian + daftar dokumen di tengah ===== --}}
<section class="relative z-10 -mt-8 md:-mt-12 mb-24">
  <div class="container mx-auto px-6 max-w-5xl">
    <div class="rounded-xl bg-white ring-1 ring-gray-100
                shadow-[0_18px_30px_-22px_rgba(0,0,0,0.14),0_8px_18px_-16px_rgba(0,0,0,0.08)]">

      <div class="px-6 md:px-10 py-8">
        {{-- Judul di atas daftar --}}
        <div class="text-center">
          <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Daftar Dokumen</h2>
          <p class="text-sm text-gray-500 mt-2">Gunakan pencarian untuk menemukan dokumen lebih cepat.</p>
        </div>

        {{-- Pencarian (center) --}}
        <div x-data="{q:'{{ addslashes($q ?? '') }}'}" class="mt-6 flex justify-center">
          <div class="relative w-full max-w-lg">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <circle cx="11" cy="11" r="7" stroke-width="2"></circle>
              <path d="M21 21l-4.3-4.3" stroke-width="2" stroke-linecap="round"></path>
            </svg>
            <input x-model="q" placeholder="Cari judul dokumen..."
                   class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-9 pr-3 py-2.5
                          focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/35 outline-none transition"/>
          </div>
        </div>

        {{-- List dokumen (rata tengah) --}}
        <ul class="mt-7 max-w-3xl mx-auto divide-y divide-gray-100">
          @forelse ($docs as $doc)
            @php
              $title = $doc['title'];
              $year  = $doc['year'] ?? null;
              $size  = $doc['size_kb'] ?? null; // KB
              $mime  = strtoupper(pathinfo($title, PATHINFO_EXTENSION) ?: ($doc['mime'] ?? 'PDF'));
              $id    = $doc['id'];
            @endphp
            <li
              x-show="!q || '{{ strtolower($title) }}'.includes(q.toLowerCase())"
              class="group flex items-stretch justify-between gap-4 px-2 md:px-1 py-3 md:py-4">

              <div class="flex items-center gap-3 min-w-0">
                {{-- Icon --}}
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-orange-50 ring-1 ring-orange-100 grid place-content-center">
                  <svg class="w-5 h-5 text-[#F16A00]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M7 3h6l5 5v13a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" stroke-width="1.7"/>
                    <path d="M13 3v6h6" stroke-width="1.7"/>
                  </svg>
                </div>

                <div class="min-w-0">
                  <p class="text-gray-800 font-medium truncate">{{ $title }}</p>
                  <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 ring-1 ring-orange-200">
                      {{ $mime === '' ? 'PDF' : $mime }}
                    </span>
                    @if($year)<span>{{ $year }}</span>@endif
                    @if($size)<span>• {{ number_format($size) }} KB</span>@endif
                  </div>
                </div>
              </div>

              <div class="flex items-center">
                <a href="{{ route('informasi.download', $id) }}"
                   class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-white text-sm font-semibold
                          bg-gradient-to-r from-[#FFA72B] to-[#F16A00] shadow-sm
                          hover:brightness-95 active:scale-[.99] transition">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16"/>
                  </svg>
                  Unduh
                </a>
              </div>
            </li>
          @empty
            <li class="py-8">
              <p class="text-center text-sm text-gray-500">Belum ada dokumen yang dipublikasikan.</p>
            </li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</section>

@endsection
