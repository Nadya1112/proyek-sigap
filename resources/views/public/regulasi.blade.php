@extends('layouts.public')

@section('title','Regulasi & Dokumen')

@section('content')

{{-- ===== HERO ===== --}}
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
        Informasi Dokumen Regulasi Resmi
      </h1>
      <p class="mt-4 text-base md:text-lg text-white/90 max-w-3xl mx-auto">
        Kumpulan regulasi, keputusan, dan dokumen pendukung SIGAP–KOMPLEK.
      </p>
    </div>
  </div>
</section>

{{-- ===== KONTEN: Diperbarui dengan Alpine.js untuk Live Search ===== --}}
<main class="py-16 lg:py-24 bg-gray-50">
  <div class="container mx-auto px-6">
    <div 
        x-data="{
            searchQuery: '{{ addslashes($q ?? '') }}',
            allDocs: {{ json_encode($docs) }},
            get filteredDocs() {
                if (this.searchQuery.trim() === '') {
                    return this.allDocs;
                }
                return this.allDocs.filter(
                    doc => doc.judul.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            }
        }" 
        class="max-w-5xl mx-auto"
    >
      <div class="rounded-xl bg-white ring-1 ring-gray-100 shadow-xl p-6 md:p-10">
        <div class="text-center">
          <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Daftar Dokumen</h2>
          <p class="text-gray-500 mt-2">Gunakan pencarian untuk menemukan dokumen lebih cepat.</p>
        </div>

        <div class="mt-6 flex justify-center">
          <div class="relative w-full max-w-lg">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7" stroke-width="2"></circle><path d="M21 21l-4.3-4.3" stroke-width="2" stroke-linecap="round"></path></svg>
            <input x-model="searchQuery" placeholder="Ketik satu suku kata untuk mencari..."
                   class="w-full rounded-full border-2 border-gray-200 bg-gray-50 pl-12 pr-4 py-3 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-400/30 outline-none transition"/>
          </div>
        </div>

        <ul class="mt-8 max-w-4xl mx-auto divide-y divide-gray-100">
          <template x-for="doc in filteredDocs" :key="doc.id">
            <li class="group flex items-center justify-between gap-4 px-2 py-4 transition-colors hover:bg-gray-50/80 rounded-lg">
              <div class="flex items-center gap-4 min-w-0">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-orange-50 ring-1 ring-orange-100 grid place-content-center">
                    <svg class="w-5 h-5 text-orange-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M7 3h6l5 5v13a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" stroke-width="1.7"/><path d="M13 3v6h6" stroke-width="1.7"/></svg>
                </div>
                <div class="min-w-0">
                  <p class="text-gray-800 font-semibold truncate" x-text="doc.judul"></p>
                  <div class="mt-1 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-orange-100 text-orange-800 font-medium" x-text="doc.tipe_file.toUpperCase()"></span>
                    <span x-show="doc.tahun" x-text="doc.tahun"></span>
                    <span x-show="doc.ukuran_file"><span class="mx-1">•</span> <span x-text="`${Math.round(doc.ukuran_file)} KB`"></span></span>
                  </div>
                </div>
              </div>

              <div class="flex items-center">
                <a :href="`{{ url('/regulasi/unduh') }}/${doc.id}`"
                   class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-white text-sm font-semibold bg-gradient-to-r from-orange-500 to-orange-600 shadow-md hover:from-orange-600 hover:to-orange-700 active:scale-[.98] transition-all transform group-hover:scale-105">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16"/></svg>
                  Unduh
                </a>
              </div>
            </li>
          </template>
          
          <template x-if="filteredDocs.length === 0">
            <li class="py-10 text-center">
                <p class="text-gray-500">Dokumen dengan kata kunci "<strong x-text="searchQuery"></strong>" tidak ditemukan.</p>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </div>
</main>

@endsection