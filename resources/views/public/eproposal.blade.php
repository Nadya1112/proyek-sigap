@extends('layouts.public')

@section('title','E-Proposal PSU')

@section('content')

{{-- ========== HERO ========== --}}
<section class="relative overflow-hidden">
  <div class="relative" style="background: linear-gradient(135deg, #F7A623 0%, #FF7A00 45%, #F25C3B 70%, #F04949 100%);">
    <div class="pointer-events-none absolute -top-6 -left-10 w-[380px] h-[380px] opacity-70" style="background: radial-gradient(closest-side, rgba(255,179,73,0.55) 0%, rgba(255,179,73,0.28) 34%, rgba(255,179,73,0.12) 60%, transparent 72%); filter: blur(2px);"></div>
    <div class="pointer-events-none absolute -bottom-10 -right-8 w-[360px] h-[360px] opacity-65" style="background: radial-gradient(closest-side, rgba(255,120,120,0.45) 0%, rgba(255,120,120,0.24) 35%, rgba(255,120,120,0.10) 58%, transparent 75%); filter: blur(2px);"></div>

    <div class="container mx-auto px-6 py-14 md:py-20 relative text-center text-white">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 ring-1 ring-white/25">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
            E-Proposal PSU
        </span>
        <h1 class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight drop-shadow-[0_1px_2px_rgba(0,0,0,0.12)]">
          Pengajuan Proposal Bantuan PSU
        </h1>
        <p class="mt-4 text-base md:text-lg text-white/90 max-w-3xl mx-auto">
          Ajukan proposal bantuan prasarana, sarana, dan utilitas perumahan. Data Anda kami proses dengan transparan dan cepat.
        </p>
        
        <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $total ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Total Proposal</p>
            </div>
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $diverifikasi ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Sudah Diverifikasi</p>
            </div>
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $diproses ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Proses Peninjauan</p>
            </div>
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $terkirim ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Belum Diverifikasi</p>
            </div>
        </div>
    </div>
  </div>
</section>

{{-- ========== FORM CARD ========== --}}
<main class="py-16 lg:py-24 bg-gray-50">
  <div class="container mx-auto px-6">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg border border-gray-100 p-8">

        {{-- Cek apakah pengguna sudah login --}}
        @auth
            {{-- JIKA SUDAH LOGIN: Tampilkan form --}}
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Formulir Pengajuan Proposal</h2>
            <p class="text-gray-600 mb-6">Isi data berikut dengan benar untuk mempercepat proses verifikasi.</p>

            @if(session('success'))
              <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
              </div>
            @endif

            <form action="{{ route('eproposal.store') }}" method="POST" enctype="multipart/form-data"
                  class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
              @csrf

              <div>
                <label for="nama_pengaju" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap Pengaju</label>
                <input id="nama_pengaju" name="nama_pengaju" value="{{ old('nama_pengaju', auth()->user()->name) }}" required
                       class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm transition
                              focus:bg-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none"/>
                @error('nama_pengaju')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
              </div>

              <div>
                <label for="kontak" class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Kontak (WA/Telepon)</label>
                <input id="kontak" name="kontak" value="{{ old('kontak', auth()->user()->kontak) }}" required
                       class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm transition
                              focus:bg-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none"/>
                @error('kontak')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
              </div>

              <div class="md:col-span-2">
                <label for="nama_perumahan" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Perumahan</label>
                <input id="nama_perumahan" name="nama_perumahan" value="{{ old('nama_perumahan') }}" required
                       class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm transition
                              focus:bg-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none"/>
                @error('nama_perumahan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
              </div>

              <div class="md:col-span-2">
                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap Perumahan</label>
                <input id="alamat" name="alamat" value="{{ old('alamat') }}" required
                       class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm transition
                              focus:bg-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none"/>
                @error('alamat')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
              </div>

              <div class="md:col-span-2">
                <label for="proposal" class="block text-sm font-semibold text-gray-700 mb-1.5">
                  Unggah Proposal <span class="font-normal text-gray-500">(Format: PDF, DOC, DOCX. Maks 10MB)</span>
                </label>
                <input id="proposal" type="file" name="proposal" accept=".pdf,.doc,.docx" required
                       class="w-full rounded-xl border border-gray-200 bg-gray-50
                          file:mr-4 file:rounded-lg file:border-0 file:bg-[#FFA72B] file:px-4 file:py-2 file:text-white
                          hover:file:brightness-95 focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 transition"/>
                @error('proposal')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
              </div>

              <div class="md:col-span-2">
                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan <span class="font-normal text-gray-500">(Opsional)</span></label>
                <textarea id="catatan" name="catatan" rows="4" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm transition focus:bg-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none">{{ old('catatan') }}</textarea>
              </div>

              <div class="md:col-span-2 flex items-center justify-end pt-4">
                <button type="submit" class="btn-gradient w-full md:w-auto">
                    <span class="relative z-10">Kirim Proposal</span>
                    <div class="btn-gradient-hover"></div>
                </button>
              </div>
            </form>
        @else
            {{-- JIKA BELUM LOGIN: Tampilkan kartu notifikasi --}}
            <div class="text-center bg-orange-50/50 rounded-xl p-8 md:p-12 border border-orange-200/80">
                        <div
                            class="mx-auto w-16 h-16 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center shadow-lg mb-5">
                            <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H4.5a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>

                <h3 class="text-2xl font-bold text-gray-800">Silakan Masuk Terlebih Dahulu</h3>
                <p class="text-gray-600 mt-2 mb-6 max-w-md mx-auto">
                    Anda harus memiliki akun dan masuk untuk dapat mengajukan proposal baru.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('login') }}" class="btn-gradient rounded-lg px-8 py-3">
                        <span class="relative z-10 font-semibold">Masuk ke Akun</span>
                        <div class="btn-gradient-hover rounded-lg"></div>
                    </a>
                    <a href="{{ route('register') }}" class="font-semibold text-orange-600 hover:text-orange-700 transition">
                        Buat Akun Baru
                    </a>
                </div>
            </div>
        @endauth
    </div>
  </div>
</main>
@endsection