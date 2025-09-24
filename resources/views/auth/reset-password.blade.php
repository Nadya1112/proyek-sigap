@extends('layouts.auth')

@section('title', 'Reset Kata Sandi - SIGAP KOMPLEK')

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
               <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>
      </div>

      {{-- Judul --}}
      <h1 class="text-center text-[20px] font-extrabold text-gray-800 tracking-wide">
        Buat Kata Sandi Baru
      </h1>
      <p class="mt-1 text-center text-[13px] text-gray-500">
        Kata sandi baru Anda harus berbeda dari yang pernah digunakan sebelumnya.
      </p>

      {{-- FORM --}}
      <form method="POST" action="{{ route('password.store') }}" class="mt-7 space-y-5">
        @csrf

        {{-- Token (wajib, jangan dihapus) --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email (wajib, jangan dihapus) --}}
        <div>
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Email</label>
          <input readonly
            type="email" name="email" value="{{ old('email', $request->email) }}" required
            class="w-full rounded-2xl border border-gray-200 bg-gray-100 text-[14px] text-gray-500
                   px-4 py-3 transition outline-none"/>
          @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Password Baru --}}
        <div x-data="{ show:false }">
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Kata Sandi Baru</label>
          <div class="relative">
            <input
              :type="show ? 'text' : 'password'" name="password" required autofocus
              placeholder="Masukkan kata sandi baru"
              class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] placeholder:text-gray-400
                     px-4 py-3 pr-11 transition
                     focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z"/><circle cx="12" cy="12" r="3.25" stroke-width="1.6"/></svg>
            </button>
          </div>
          @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Konfirmasi Password Baru --}}
        <div>
          <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
          <input
            type="password" name="password_confirmation" required
            placeholder="Ulangi kata sandi baru"
            class="w-full rounded-2xl border border-gray-200 bg-[#F7F8FA] text-[14px] placeholder:text-gray-400
                   px-4 py-3 transition
                   focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"/>
        </div>

        {{-- Tombol Reset --}}
        <button type="submit"
          class="w-full rounded-2xl py-3 text-white font-semibold shadow-sm
                 bg-gradient-to-r from-[#FFA72B] to-[#F16A00]
                 hover:brightness-95 active:scale-[.99]
                 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#F39B28] transition">
          Reset Kata Sandi
        </button>
      </form>
    </div>
  </div>
</div>
@endsection