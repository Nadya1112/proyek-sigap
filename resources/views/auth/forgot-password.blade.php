@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi - SIGAP KOMPLEK')

@section('content')
<div class="min-h-screen flex items-center justify-center relative px-4">

  {{-- Latar belakang --}}
  <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-24 -left-24 w-[520px] h-[520px] rounded-full opacity-20 blur-3xl"
         style="background: radial-gradient(50% 50% at 30% 30%, rgba(255,160,67,.50) 0%, rgba(255,160,67,.08) 60%, transparent 70%);"></div>
  </div>

  {{-- CARD --}}
  <div class="w-full max-w-[460px] bg-white rounded-[26px] shadow-xl border border-gray-100">
    <div class="px-8 pt-9 pb-9">

      {{-- Ikon --}}
      <div class="flex justify-center mb-5">
        <div class="w-16 h-16 rounded-full bg-orange-100 grid place-content-center">
            <svg class="w-8 h-8 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
            </svg>
        </div>
      </div>

      {{-- Judul --}}
      <h1 class="text-center text-[20px] font-extrabold text-gray-800 tracking-wide">
        Lupa Kata Sandi?
      </h1>
      <p class="mt-1 text-center text-[13px] text-gray-500">
        Jangan khawatir. Masukkan email Anda dan kami akan mengirimkan link untuk mereset kata sandi Anda.
      </p>

      {{-- Tampilkan status jika link berhasil dikirim --}}
      @if (session('status'))
        <div class="mt-6 bg-green-50 text-green-700 text-sm font-semibold p-4 rounded-xl text-center">
            {{ session('status') }}
        </div>
      @endif

      {{-- FORM --}}
      <form method="POST" action="{{ route('password.email') }}" class="mt-7 space-y-5">
        @csrf

        {{-- Email --}}
        <div>
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Alamat Email Terdaftar</label>
          <input
            type="email" name="email" value="{{ old('email') }}" required autofocus
            placeholder="nama@email.com"
            class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] placeholder:text-gray-400
                   px-4 py-3 transition
                   focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
          @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Tombol Kirim --}}
        <button type="submit"
          class="w-full rounded-2xl py-3 text-white font-semibold shadow-sm
                 bg-gradient-to-r from-[#FFA72B] to-[#F16A00]
                 hover:brightness-95 active:scale-[.99]
                 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#F39B28] transition">
          Kirim Link Reset
        </button>
      </form>

      {{-- Link kembali ke login --}}
      <p class="mt-6 text-center text-[13px] text-gray-500">
        Ingat kata sandi Anda?
        <a href="{{ route('login') }}" class="text-[#F39B28] font-semibold hover:underline">Kembali ke Login</a>
      </p>
    </div>
  </div>
</div>
@endsection