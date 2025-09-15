@extends('layouts.public')

@section('title','Pengaduan Masyarakat')

@section('content')

{{-- ===== HERO (gradasi & glow) + STATISTIK ===== --}}
<section class="relative overflow-hidden">
  <div class="relative"
       style="background: linear-gradient(135deg, #F7A623 0%, #FF7A00 45%, #F25C3B 70%, #F04949 100%);">
    {{-- glow kiri-atas --}}
    <div class="pointer-events-none absolute -top-6 -left-10 w-[360px] h-[360px] opacity-70"
         style="background: radial-gradient(closest-side, rgba(255,179,73,0.55) 0%, rgba(255,179,73,0.26) 34%, rgba(255,179,73,0.10) 60%, transparent 72%); filter: blur(2px);"></div>
    {{-- glow kanan-bawah --}}
    <div class="pointer-events-none absolute -bottom-10 -right-8 w-[340px] h-[340px] opacity-65"
         style="background: radial-gradient(closest-side, rgba(255,120,120,0.45) 0%, rgba(255,120,120,0.22) 35%, rgba(255,120,120,0.10) 58%, transparent 75%); filter: blur(2px);"></div>

    <div class="container mx-auto px-6 py-14 md:py-20 relative text-center text-white">
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 ring-1 ring-white/25">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
        </svg>
        Layanan Pengaduan
      </span>

      <h1 class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight drop-shadow-[0_1px_2px_rgba(0,0,0,0.12)]">
        Pengaduan Masyarakat
      </h1>
      <p class="mt-4 text-base md:text-lg text-white/90 max-w-3xl mx-auto">
        Sampaikan keluhan atau masukan Anda terkait PSU perumahan. Kami menindaklanjuti secara cepat,
        transparan, dan bertanggung jawab.
      </p>

      {{-- STATISTIK: bg putih low-opacity + hover lift, caption di bawah --}}
      <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
        @php
          $statTotal   = $total   ?? 0;
          $statSelesai = $selesai ?? 0;   // Sudah ditindaklanjuti
          $statProses  = $proses  ?? 0;   // Dalam proses
          $statBelum   = $belum   ?? 0;   // Belum ditindaklanjuti
        @endphp

        <div
          class="group rounded-[14px] px-8 py-6 text-center
                 bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)]
                 transition-all duration-300
                 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
          <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $statTotal }}</p>
          <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Total Pengaduan</p>
        </div>

        <div
          class="group rounded-[14px] px-8 py-6 text-center
                 bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)]
                 transition-all duration-300
                 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
          <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $statSelesai }}</p>
          <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Sudah Ditindaklanjuti</p>
        </div>

        <div
          class="group rounded-[14px] px-8 py-6 text-center
                 bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)]
                 transition-all duration-300
                 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
          <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $statProses }}</p>
          <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Dalam Proses</p>
        </div>

        <div
          class="group rounded-[14px] px-8 py-6 text-center
                 bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)]
                 transition-all duration-300
                 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
          <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $statBelum }}</p>
          <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Belum Ditindaklanjuti</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ===== FORM CARD ===== --}}
<section class="relative z-10 -mt-8 md:-mt-12 mb-28">
  <div class="container mx-auto px-6">
    @if(session('success'))
      <div class="mb-6 rounded-lg border border-green-200 bg-green-50 text-green-700 px-4 py-3">
        {{ session('success') }}
      </div>
    @endif

    <div class="mx-auto max-w-5xl rounded-xl bg-white ring-1 ring-gray-100
                shadow-[0_18px_30px_-22px_rgba(0,0,0,0.16),0_8px_18px_-16px_rgba(0,0,0,0.08)]">
      <div class="px-6 md:px-10 py-6 border-b border-gray-100">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Form Pengaduan</h2>
        <p class="text-sm text-gray-500 mt-1">Isi data di bawah dengan jelas agar petugas dapat menindaklanjuti.</p>
      </div>

      <div class="px-6 md:px-10 py-8">
        <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data"
              class="grid grid-cols-1 gap-6">
          @csrf

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nama (wajib) --}}
            <div>
              <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama</label>
              <input id="nama" name="nama" value="{{ old('nama') }}" required
                     class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                            focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 outline-none transition"/>
              @error('nama')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Kontak (wajib) --}}
            <div>
              <label for="kontak" class="block text-sm font-semibold text-gray-700 mb-1.5">Kontak</label>
              <input id="kontak" name="kontak" value="{{ old('kontak') }}" required
                     inputmode="tel email" pattern="[0-9+\- ]{8,20}|.+@.+\..+" title="Isi nomor HP (8–20 digit) atau email yang valid"
                     class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                            focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 outline-none transition"/>
              @error('kontak')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
          </div>

          {{-- Isi Pengaduan (wajib) --}}
          <div>
            <label for="isi" class="block text-sm font-semibold text-gray-700 mb-1.5">Isi Pengaduan</label>
            <textarea id="isi" name="isi" rows="6" required
                      class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                             focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 outline-none transition">{{ old('isi') }}</textarea>
            @error('isi')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
          </div>

          {{-- Bukti foto (opsional) --}}
          <div>
            <label for="bukti" class="block text-sm font-semibold text-gray-700 mb-1.5">
              Bukti Foto <span class="font-normal text-gray-500">(opsional, JPG/PNG maks 5MB)</span>
            </label>
            <input id="bukti" type="file" name="bukti" accept="image/*"
                   class="w-full rounded-xl border border-gray-200 bg-gray-50
                          file:mr-4 file:rounded-lg file:border-0 file:bg-[#FFA72B] file:px-4 file:py-2 file:text-white
                          hover:file:brightness-95 focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 transition"/>
            @error('bukti')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
          </div>

          <div class="flex items-center justify-end">
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold text-white
                           bg-gradient-to-r from-[#FFA72B] to-[#F16A00] shadow-sm
                           hover:brightness-95 active:scale-[.99]
                           focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#F39B28] transition">
              Kirim Pengaduan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

@endsection
