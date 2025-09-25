@extends('layouts.auth')

@section('title','Daftar Akun - SIGAP KOMPLEK')

@section('content')
<div class="min-h-screen flex items-center justify-center relative px-4">

  {{-- Siluet lembut --}}
  <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-24 -left-24 w-[520px] h-[520px] rounded-full opacity-20 blur-3xl"
         style="background: radial-gradient(50% 50% at 30% 30%, rgba(255,160,67,.50) 0%, rgba(255,160,67,.08) 60%, transparent 70%);"></div>
    <div class="absolute -bottom-28 -right-28 w-[560px] h-[560px] rounded-full opacity-10 blur-3xl"
         style="background: radial-gradient(50% 50% at 70% 70%, rgba(255,140,0,.45) 0%, rgba(255,140,0,.06) 60%, transparent 75%);"></div>
  </div>

  {{-- CARD REGISTER --}}
    <div class="w-full max-w-md md:max-w-lg bg-white rounded-[26px] shadow-xl border border-gray-100 mx-auto">
    <div class="px-8 pt-9 pb-9">

      {{-- Logo --}}
      <div class="flex justify-center mb-5">
        <img src="{{ asset('img/logo-sigap.png') }}" alt="Logo" class="h-14 w-auto object-contain">
      </div>

      {{-- Judul --}}
      <h1 class="text-center text-[20px] font-extrabold text-gray-800 tracking-wide">
        Buat Akun SIGAP – KOMPLEK
      </h1>
      <p class="mt-1 text-center text-[13px] text-gray-500">
        Daftar untuk menggunakan layanan SIGAP – KOMPLEK
      </p>

      {{-- FORM DAFTAR --}}
      <form method="POST" action="{{ route('register.post') }}" class="mt-7 space-y-5">
        @csrf

        {{-- Nama lengkap (wajib) --}}
        <div>
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Nama lengkap</label>
          <input required type="text" name="name" value="{{ old('name') }}"
                 placeholder="Masukkan nama lengkap"
                 class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] placeholder:text-gray-400
                        px-4 py-3 transition focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
          @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Email (wajib) --}}
        <div>
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Email</label>
          <input required type="email" name="email" value="{{ old('email') }}"
                 placeholder="nama@email.com"
                 class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] placeholder:text-gray-400
                        px-4 py-3 transition focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
          @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- {{-- Username (wajib) --}}
        <div>
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Username</label>
          <input required type="text" name="username" value="{{ old('username') }}"
                 placeholder="Nama pengguna"
                 class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] placeholder:text-gray-400
                        px-4 py-3 transition focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
          @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div> -->

        {{-- Nomor HP (wajib) --}}
<div>
  <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Nomor HP</label>
  <input required type="tel" name="kontak" value="{{ old('kontak') }}" inputmode="numeric"
         placeholder="08xxxxxxxxxx"
         class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] placeholder:text-gray-400
                px-4 py-3 transition focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
  @error('kontak') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

        {{-- Kata sandi (wajib, min 8) --}}
        <div x-data="{ show:false }">
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Kata sandi</label>
          <div class="relative">
            <input required minlength="8" :type="show ? 'text' : 'password'" name="password"
                   placeholder="Buat kata sandi (min. 8 karakter)"
                   class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] placeholder:text-gray-400
                          px-4 py-3 pr-11 transition focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
            <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
                    aria-label="Tampilkan/sembunyikan kata sandi">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                      d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z"/>
                <circle cx="12" cy="12" r="3.25" stroke-width="1.6"/>
              </svg>
            </button>
          </div>
          @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Konfirmasi kata sandi (wajib, min 8) --}}
        <div x-data="{ show:false }">
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Konfirmasi kata sandi</label>
          <div class="relative">
            <input required minlength="8" :type="show ? 'text' : 'password'" name="password_confirmation"
                   placeholder="Ulangi kata sandi"
                   class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] placeholder:text-gray-400
                          px-4 py-3 pr-11 transition focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
            <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
                    aria-label="Tampilkan/sembunyikan kata sandi">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                      d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z"/>
                <circle cx="12" cy="12" r="3.25" stroke-width="1.6"/>
              </svg>
            </button>
          </div>
        </div>

{{-- S&K (wajib) --}}
        <label class="inline-flex items-start gap-3 text-[13px] text-gray-600">
          <input required type="checkbox" name="syaratdanketentuan"
                 class="mt-[3px] rounded border-gray-300 text-[#F39B28] focus:ring-[#F39B28]">
          <span>Saya menyetujui <a href="{{ route('syaratdanketentuan') }}" target="_blank" class="text-[#F39B28] hover:underline">Syarat & Ketentuan</a> serta <a href="{{ route('kebijakanprivasi') }}" target="_blank" class="text-[#F39B28] hover:underline">Kebijakan Privasi</a>.</span>
        </label>

        {{-- Tombol Daftar --}}
        <button type="submit"
                class="w-full rounded-2xl py-3 text-white font-semibold shadow-sm
                       bg-gradient-to-r from-[#FFA72B] to-[#F16A00]
                       hover:brightness-95 active:scale-[.99]
                       focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#F39B28] transition">
          Daftar
        </button>
      </form>

      {{-- Link ke login --}}
      <p class="mt-6 text-center text-[13px] text-gray-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-[#F39B28] font-semibold hover:underline">Masuk di sini</a>
      </p>
    </div>
  </div>
</div>
@endsection
