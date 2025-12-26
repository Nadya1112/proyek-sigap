@extends('layouts.public')

@section('title','Regulasi & Dokumen')

@section('content')

{{-- ===== HERO SECTION (Header) ===== --}}
<section class="relative overflow-hidden">
  <div class="relative" style="background: linear-gradient(135deg, #F7A623 0%, #FF7A00 45%, #F25C3B 70%, #F04949 100%);">
    <div class="pointer-events-none absolute -top-6 -left-10 w-[360px] h-[360px] opacity-70" style="background: radial-gradient(closest-side, rgba(255,179,73,0.55) 0%, rgba(255,179,73,0.26) 34%, rgba(255,179,73,0.10) 60%, transparent 72%); filter: blur(2px);"></div>
    <div class="pointer-events-none absolute -bottom-10 -right-8 w-[340px] h-[340px] opacity-65" style="background: radial-gradient(closest-side, rgba(255,120,120,0.45) 0%, rgba(255,120,120,0.22) 35%, rgba(255,120,120,0.10) 58%, transparent 75%); filter: blur(2px);"></div>

    <div class="container mx-auto px-6 py-14 md:py-20 relative text-center text-white">
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 ring-1 ring-white/25">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
        Pusat Informasi
      </span>
      <h1 class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight drop-shadow-sm">
        Informasi Dokumen Regulasi Resmi
      </h1>
      <p class="mt-4 text-base md:text-lg text-white/90 max-w-3xl mx-auto">
        Kumpulan regulasi, keputusan, dan dokumen pendukung SIGAP–KOMPLEK.
      </p>
    </div>
  </div>
</section>

{{-- ===== KONTEN UTAMA ===== --}}
<main class="py-16 bg-gray-50 min-h-[600px]">
  <div class="container mx-auto px-6">
    <div class="max-w-5xl mx-auto">
      
      {{-- Card Container --}}
      <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        
        {{-- Header Card --}}
        <div class="p-6 md:p-8 border-b border-gray-100 text-center">
          <h2 class="text-2xl font-bold text-gray-800">Daftar Dokumen</h2>
          <p class="text-gray-500 mt-1 text-sm">Gunakan filter di bawah untuk menemukan dokumen yang Anda cari.</p>
        </div>

        {{-- Form Filter --}}
        <div class="bg-gray-50/50 p-6 md:p-8">
            <form method="GET" action="{{ route('regulasi') }}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    {{-- Input Pencarian --}}
                    <div class="md:col-span-5">
                        <label for="q" class="block text-sm font-semibold text-gray-700 mb-1">Kata Kunci</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" name="q" id="q" value="{{ $input['q'] ?? '' }}" 
                                   placeholder="Cari judul dokumen..."
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition text-sm">
                        </div>
                    </div>

                    {{-- Filter Tahun --}}
                    <div class="md:col-span-3">
                        <label for="tahun" class="block text-sm font-semibold text-gray-700 mb-1">Tahun</label>
                        <select name="tahun" id="tahun" class="w-full py-2 px-3 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition text-sm bg-white">
                            <option value="">Semua Tahun</option>
                            @foreach($filterTahun as $tahun)
                                <option value="{{ $tahun }}" @selected(($input['tahun'] ?? '') == $tahun)>{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Jenis --}}
                    <div class="md:col-span-3">
                        <label for="jenis" class="block text-sm font-semibold text-gray-700 mb-1">Jenis Dokumen</label>
                        <select name="jenis" id="jenis" class="w-full py-2 px-3 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition text-sm bg-white">
                            <option value="">Semua Jenis</option>
                            @foreach($filterJenis as $jenis)
                                <option value="{{ $jenis }}" @selected(($input['jenis'] ?? '') == $jenis)>{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="md:col-span-1">
                        <button type="submit" class="w-full py-2 px-4 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg shadow-md transition active:scale-95 flex justify-center items-center h-[38px]" title="Terapkan Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Daftar Dokumen --}}
        <div class="p-6 md:p-8">
          <div class="space-y-4">
            @forelse($docs as $doc)
              <div class="group flex flex-col md:flex-row items-start md:items-center justify-between gap-4 p-5 rounded-xl border border-gray-100 hover:border-orange-200 bg-white hover:bg-orange-50/30 transition-all duration-300 shadow-sm hover:shadow-md">
                
                {{-- Icon & Info --}}
                <div class="flex items-start gap-4 w-full">
                  {{-- Icon Dokumen --}}
                  <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                  </div>

                  {{-- Text Info --}}
                  <div class="flex-grow min-w-0">
                    <h3 class="text-gray-900 font-bold text-base md:text-lg leading-tight group-hover:text-orange-700 transition-colors">
                        {{ $doc->judul }}
                    </h3>
                    
                    <div class="mt-2 flex flex-wrap items-center gap-2 text-xs md:text-sm text-gray-500">
                        {{-- Badge Jenis --}}
                        @if($doc->jenis_dokumen)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800">
                                {{ $doc->jenis_dokumen }}
                            </span>
                        @endif

                        {{-- Badge Tipe File --}}
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-gray-100 text-gray-700 border border-gray-200">
                            {{ strtoupper($doc->tipe_file) }}
                        </span>

                        {{-- Metadata --}}
                        @if($doc->tahun)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $doc->tahun }}
                            </span>
                        @endif
                        
                        @if($doc->ukuran_file)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                {{ round($doc->ukuran_file) }} KB
                            </span>
                        @endif
                    </div>
                  </div>
                </div>

                {{-- Tombol Download --}}
                <div class="flex-shrink-0 w-full md:w-auto mt-3 md:mt-0">
                  <a href="{{ route('regulasi.download', $doc->id) }}" 
                     class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition shadow-sm active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh
                  </a>
                </div>

              </div>
            @empty
              {{-- Empty State --}}
              <div class="py-12 text-center">
                  <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                      <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                  </div>
                  <h3 class="text-lg font-medium text-gray-900">Dokumen Tidak Ditemukan</h3>
                  <p class="text-gray-500 mt-1 max-w-md mx-auto">Maaf, kami tidak dapat menemukan dokumen dengan kriteria pencarian Anda. Coba kata kunci lain atau reset filter.</p>
                  <a href="{{ route('regulasi') }}" class="mt-4 inline-block text-orange-600 font-semibold hover:underline">Reset Filter</a>
              </div>
            @endforelse
          </div>

          {{-- Paginasi --}}
          @if($docs->hasPages())
            <div class="mt-8 pt-6 border-t border-gray-100">
                {{ $docs->links() }}
            </div>
          @endif

        </div>
      </div>
    </div>
  </div>
</main>

@endsection