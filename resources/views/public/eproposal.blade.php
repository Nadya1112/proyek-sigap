@extends('layouts.public')

@section('title','E-Proposal PSU')

@section('content')

{{-- ========== HERO (gradasi seperti mockup + glow kiri/kanan) ========== --}}
<section class="relative overflow-hidden">
  <div
    class="relative"
    style="
      /* gradasi utama: kiri-atas (oranye) -> kanan-bawah (merah) */
      background: linear-gradient(135deg, #F7A623 0%, #FF7A00 45%, #F25C3B 70%, #F04949 100%);
    "
  >
    {{-- glow kiri-atas --}}
    <div class="pointer-events-none absolute -top-6 -left-10 w-[380px] h-[380px] opacity-70"
         style="background: radial-gradient(closest-side, rgba(255,179,73,0.55) 0%, rgba(255,179,73,0.28) 34%, rgba(255,179,73,0.12) 60%, transparent 72%); filter: blur(2px);">
    </div>
    {{-- glow kanan-bawah --}}
    <div class="pointer-events-none absolute -bottom-10 -right-8 w-[360px] h-[360px] opacity-65"
         style="background: radial-gradient(closest-side, rgba(255,120,120,0.45) 0%, rgba(255,120,120,0.24) 35%, rgba(255,120,120,0.10) 58%, transparent 75%); filter: blur(2px);">
    </div>

    <div class="container mx-auto px-6 py-14 md:py-20 relative">
      {{-- Badge + heading + description --}}
      <div class="max-w-4xl mx-auto text-center text-white">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 ring-1 ring-white/25">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
          </svg>
          E-Proposal PSU
        </span>

        {{-- SHADOW TIPIS pada judul --}}
        <h1 class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight drop-shadow-[0_1px_2px_rgba(0,0,0,0.12)]">
          Pengajuan Proposal Bantuan PSU
        </h1>

        <p class="mt-4 text-base md:text-lg text-white/90">
          Ajukan proposal bantuan prasarana, sarana, dan utilitas perumahan. Data Anda kami
          proses dengan transparan dan cepat.
        </p>
      </div>

{{-- Kartu statistik: bg putih low-opacity + hover lift, caption di bawah --}}
<div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
  @foreach (['Total Proposal','Sudah Diverifikasi','Proses Peninjauan','Belum Diverifikasi'] as $label)
    <div
      class="group rounded-[14px] px-8 py-6 text-center
             bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)]
             transition-all duration-300
             hover:-translate-y-1 hover:shadow-xl hover:ring-white/45
             focus-within:-translate-y-1 focus-within:ring-white/45">
      {{-- Angka --}}
      <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">0</p>
      {{-- Caption di bawah --}}
      <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">
        {{ $label }}
      </p>
    </div>
  @endforeach
</div>

</section>

{{-- ========== FORM CARD (shadow lebih halus + seluruh field wajib) ========== --}}
<section class="relative z-10 -mt-8 md:-mt-12 mb-28">
  <div class="container mx-auto px-6">
    @if(session('success'))
      <div class="mb-6 rounded-lg border border-green-200 bg-green-50 text-green-700 px-4 py-3">
        {{ session('success') }}
      </div>
    @endif

    {{-- shadow halus (opacity dikurangi) --}}
    <div class="mx-auto max-w-6xl rounded-xl bg-white ring-1 ring-gray-100
                shadow-[0_18px_30px_-22px_rgba(0,0,0,0.16),0_8px_18px_-16px_rgba(0,0,0,0.08)]">
      <div class="px-6 md:px-10 py-6 border-b border-gray-100">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Form Pengajuan</h2>
        <p class="text-sm text-gray-500 mt-1">Isi data berikut dengan benar untuk mempercepat proses verifikasi.</p>
      </div>

      <div class="px-6 md:px-10 py-8">
        <form action="{{ route('eproposal.store') }}" method="POST" enctype="multipart/form-data"
              class="grid grid-cols-1 md:grid-cols-2 gap-6">
          @csrf

          {{-- Nama Pengaju (wajib) --}}
          <div>
            <label for="nama_pengaju" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Pengaju</label>
            <input id="nama_pengaju" name="nama_pengaju" value="{{ old('nama_pengaju') }}" required
                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                          focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 outline-none transition"/>
            @error('nama_pengaju')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
          </div>

          {{-- Nomor Kontak (wajib + pattern sederhana) --}}
          <div>
            <label for="kontak" class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Kontak</label>
            <input id="kontak" name="kontak" value="{{ old('kontak') }}" required
                   inputmode="tel" pattern="[0-9+\- ]{8,20}" title="Isi nomor HP yang valid (8–20 digit, boleh + atau -)"
                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                          focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 outline-none transition"/>
            @error('kontak')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
          </div>

          {{-- Nama Perumahan (wajib) --}}
          <div>
            <label for="nama_perumahan" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Perumahan</label>
            <input id="nama_perumahan" name="nama_perumahan" value="{{ old('nama_perumahan') }}" required
                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                          focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 outline-none transition"/>
            @error('nama_perumahan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
          </div>

          {{-- Alamat (wajib) --}}
          <div>
            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Perumahan</label>
            <input id="alamat" name="alamat" value="{{ old('alamat') }}" required
                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                          focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 outline-none transition"/>
            @error('alamat')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
          </div>

          {{-- File Proposal (wajib) --}}
          <div class="md:col-span-2">
            <label for="proposal" class="block text-sm font-semibold text-gray-700 mb-1.5">
              Unggah Proposal <span class="font-normal text-gray-500">(PDF/DOCX, maks 10MB)</span>
            </label>
            <input id="proposal" type="file" name="proposal" accept=".pdf,.doc,.docx" required
                   class="w-full rounded-xl border border-gray-200 bg-gray-50
                          file:mr-4 file:rounded-lg file:border-0 file:bg-[#FFA72B] file:px-4 file:py-2 file:text-white
                          hover:file:brightness-95 focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 transition"/>
            @error('proposal')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
          </div>

          {{-- Catatan (opsional) --}}
          <div class="md:col-span-2">
            <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-1.5">
              Catatan <span class="font-normal text-gray-500">(opsional)</span>
            </label>
            <textarea id="catatan" name="catatan" rows="4"
                      class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                             focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 outline-none transition">{{ old('catatan') }}</textarea>
          </div>

          {{-- Submit --}}
          <div class="md:col-span-2 flex items-center justify-end">
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold text-white
                           bg-gradient-to-r from-[#FFA72B] to-[#F16A00] shadow-sm
                           hover:brightness-95 active:scale-[.99]
                           focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#F39B28] transition">
              Kirim Proposal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

@endsection
