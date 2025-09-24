@extends('layouts.auth')

@section('title','Masuk - SIGAP KOMPLEK')

@section('content')
<div class="min-h-screen flex items-center justify-center relative px-4">

  {{-- Siluet lembut --}}
  <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-24 -left-24 w-[520px] h-[520px] rounded-full opacity-20 blur-3xl"
         style="background: radial-gradient(50% 50% at 30% 30%, rgba(255,160,67,.50) 0%, rgba(255,160,67,.08) 60%, transparent 70%);"></div>
    <div class="absolute -bottom-28 -right-28 w-[560px] h-[560px] rounded-full opacity-10 blur-3xl"
         style="background: radial-gradient(50% 50% at 70% 70%, rgba(255,140,0,.45) 0%, rgba(255,140,0,.06) 60%, transparent 75%);"></div>
  </div>

  {{-- CARD LOGIN --}}
  <div class="w-full max-w-[460px] bg-white rounded-[26px] shadow-xl border border-gray-100">
    <div class="px-8 pt-9 pb-9">

      {{-- Logo (ubah path jika perlu) --}}
      <div class="flex justify-center mb-5">
        <img src="{{ asset('img/logo-sigap.png') }}" alt="Logo" class="h-14 w-auto object-contain">
        {{-- contoh ganti: asset('img/logo-pemkot.png') --}}
      </div>

      {{-- Judul --}}
      <h1 class="text-center text-[20px] font-extrabold text-gray-800 tracking-wide">
        SIGAP – KOMPLEK
      </h1>
      <p class="mt-1 text-center text-[13px] text-gray-500">
        Silakan masuk terlebih dahulu
      </p>

      {{-- FORM --}}
      <form method="POST" action="{{ route('login.post') }}" class="mt-7 space-y-5">
        @csrf

        {{-- Login --}}
        <div>
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Login</label>
          <input
            type="text" name="login" value="{{ old('login') }}" required
            placeholder="Email / Nomor HP "
            class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] placeholder:text-gray-400
                   px-4 py-3 transition
                   focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
          @error('login') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Password --}}
        <div x-data="{ show:false }">
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Kata sandi</label>
          <div class="relative">
            <input
              :type="show ? 'text' : 'password'" name="password" required
              placeholder="Masukkan kata sandi"
              class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] placeholder:text-gray-400
                     px-4 py-3 pr-11 transition
                     focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
            <button type="button" @click="show = !show"
              class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
              aria-label="Tampilkan/sembunyikan kata sandi">
              {{-- ikon "eye" --}}
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                      d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z"/>
                <circle cx="12" cy="12" r="3.25" stroke-width="1.6"/>
              </svg>
            </button>
          </div>
          @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

          {{-- Lupa password DI BAWAH kolom password --}}
          <a href="{{ route('password.request') }}" class="block text-right text-[13px] text-[#F39B28] hover:underline mt-1">
            Lupa kata sandi?
          </a>
        </div>

        {{-- 🔒 Verifikasi Manusia (CAPTCHA) – tepat di bawah password --}}
        <div>
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Verifikasi manusia</label>
          <div class="flex items-center gap-3">
            <span class="text-[14px] text-gray-700 whitespace-nowrap">
              Berapa hasil: <strong>{{ $a }}</strong> + <strong>{{ $b }}</strong> = ?
            </span>
            <input type="number" inputmode="numeric" name="captcha" value="{{ old('captcha') }}"
                   class="ml-auto w-28 rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] px-3 py-2
                          focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
          </div>
          @error('captcha') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Ingat saya --}}
        <label class="inline-flex items-center gap-2 text-[13px] text-gray-600">
          <input type="checkbox" name="remember"
                 class="rounded border-gray-300 text-[#F39B28] focus:ring-[#F39B28]">
          <span>Ingat saya</span>
        </label>

        {{-- Tombol Masuk: GRADIENT ORANYE --}}
        <button type="submit"
          class="w-full rounded-2xl py-3 text-white font-semibold shadow-sm
                 bg-gradient-to-r from-[#FFA72B] to-[#F16A00]
                 hover:brightness-95 active:scale-[.99]
                 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#F39B28] transition">
          Masuk
        </button>
      </form>

      {{-- Divider "atau" --}}
      <div class="relative mt-7">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center">
          <span class="bg-white px-3 text-[12px] text-gray-400">atau</span>
        </div>
      </div>

      {{-- Tombol Google (SVG inline agar rapi) --}}
      <a href="#"
         class="mt-6 w-full inline-flex justify-center items-center gap-2 rounded-2xl border border-gray-300 bg-white py-3
                text-[14px] font-medium text-gray-700 hover:bg-gray-50
                focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#F39B28] transition">
        <svg class="h-4 w-4" viewBox="0 0 533.5 544.3" aria-hidden="true">
          <path fill="#4285F4" d="M533.5 278.4c0-18.5-1.7-36.3-4.9-53.5H272v101.1h146.9c-6.3 34.1-25.2 62.9-53.8 82.3l87 67.6c50.8-46.8 80.4-115.8 80.4-197.5z"/>
          <path fill="#34A853" d="M272 544.3c72.6 0 133.6-24 178.1-65.5l-87-67.6c-24.2 16.3-55.1 26.1-91.1 26.1-69.9 0-129.2-47.2-150.4-110.6l-90.6 70.1c43.8 87.1 133.9 147.5 241 147.5z"/>
          <path fill="#FBBC05" d="M121.6 327.1c-10.2-30.5-10.2-63.4 0-93.9l-90.6-70.1c-37.9 75.9-37.9 158.3 0 234.1l90.6-70.1z"/>
          <path fill="#EA4335" d="M272 106.1c39.5-.6 77.3 14 106.2 41.2l79.3-79.3C407.4 22.1 342 0 272 0 164.9 0 74.8 60.4 31 147.5l90.6 70.1C142.8 154.2 202.1 106.1 272 106.1z"/>
        </svg>
        <span>Masuk dengan Google</span>
      </a>

      {{-- Link daftar --}}
      <p class="mt-6 text-center text-[13px] text-gray-500">
        Belum punya akun?
        <a href="{{ url('/register') }}" class="text-[#F39B28] font-semibold hover:underline">Daftar sekarang</a>
      </p>
    </div>
  </div>
</div>
@endsection
