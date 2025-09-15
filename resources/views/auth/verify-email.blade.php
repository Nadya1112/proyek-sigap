@extends('layouts.auth')

@section('title','Verifikasi Email - SIGAP KOMPLEK')

@section('content')
<div class="min-h-screen flex items-center justify-center relative px-4">

  {{-- Siluet lembut --}}
  <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-24 -left-24 w-[520px] h-[520px] rounded-full opacity-20 blur-3xl"
         style="background: radial-gradient(50% 50% at 30% 30%, rgba(255,160,67,.50) 0%, rgba(255,160,67,.08) 60%, transparent 70%);"></div>
    <div class="absolute -bottom-28 -right-28 w-[560px] h-[560px] rounded-full opacity-10 blur-3xl"
         style="background: radial-gradient(50% 50% at 70% 70%, rgba(255,140,0,.45) 0%, rgba(255,140,0,.06) 60%, transparent 75%);"></div>
  </div>

  {{-- CARD --}}
  <div class="w-full max-w-md md:max-w-lg bg-white rounded-[26px] shadow-xl border border-gray-100 mx-auto">
    <div class="px-8 pt-9 pb-9">

      {{-- Logo --}}
      <div class="flex justify-center mb-5">
        <img src="{{ asset('img/logo-sigap.png') }}" alt="Logo" class="h-14 w-auto object-contain">
      </div>

      <h1 class="text-center text-[20px] font-extrabold text-gray-800 tracking-wide">
        Verifikasi Email
      </h1>
      <p class="mt-1 text-center text-[13px] text-gray-500">
        Kami telah mengirim kode verifikasi 4 digit ke <strong>{{ $email }}</strong>.
      </p>

      {{-- Alert status --}}
      @if (session('status'))
        <div class="mt-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl px-4 py-2">
          {{ session('status') }}
        </div>
      @endif

      {{-- FORM KODE --}}
      <form method="POST" action="{{ route('verification.verify') }}" class="mt-7 space-y-5">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}"/>

        <div>
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Masukkan kode verifikasi</label>
          <input autofocus required inputmode="numeric" pattern="[0-9]*" maxlength="4" name="code"
                 placeholder="4 digit"
                 class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[18px] tracking-[8px] text-center
                        px-4 py-3 transition focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
          @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full rounded-2xl py-3 text-white font-semibold shadow-sm
                       bg-gradient-to-r from-[#FFA72B] to-[#F16A00]
                       hover:brightness-95 active:scale-[.99]
                       focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#F39B28] transition">
          Verifikasi
        </button>
      </form>

      {{-- Kirim ulang --}}
      <form method="POST" action="{{ route('verification.resend') }}" class="mt-4 text-center">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}"/>
        <button type="submit" class="text-[#F39B28] font-semibold hover:underline text-[13px]">
          Kirim ulang kode
        </button>
      </form>

      {{-- Kembali ke login --}}
      <p class="mt-6 text-center text-[13px] text-gray-500">
        Sudah terverifikasi?
        <a href="{{ route('login') }}" class="text-[#F39B28] font-semibold hover:underline">Masuk</a>
      </p>
    </div>
  </div>
</div>
@endsection
