@extends('layouts.public')

@section('title', 'Kelola Profil')

@section('content')

{{-- Latar Belakang Gradien --}}
<div class="absolute top-0 left-0 w-full h-[400px]" style="background: linear-gradient(180deg, #F3F4F6 0%, #FFFFFF 100%); z-index: -1;"></div>

<main class="container mx-auto px-6 py-12">
    {{-- Header Halaman --}}
    <div class="text-center max-w-2xl mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight">Pengaturan Akun</h1>
        <p class="mt-3 text-lg text-gray-600">Kelola informasi akun, preferensi, dan keamanan Anda di satu tempat.</p>
    </div>

    {{-- Layout Utama 2 Kolom --}}
    <div class="mt-12 grid grid-cols-1 lg:grid-cols-4 gap-12">

        {{-- Kolom Navigasi Kiri --}}
        <aside class="lg:col-span-1">
            <nav class="space-y-2">
                <a href="#informasi-akun" class="group flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-semibold hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    <span>Informasi Akun</span>
                </a>
                <a href="#keamanan" class="group flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-semibold hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286zm0 13.036h.008v.008h-.008v-.008z" /></svg>
                    <span>Keamanan</span>
                </a>
                <a href="#zona-berbahaya" class="group flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-semibold hover:bg-red-100 text-red-600 transition-colors">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    <span>Zona Berbahaya</span>
                </a>
            </nav>
        </aside>

        {{-- Kolom Konten Kanan --}}
        <div class="lg:col-span-3 space-y-10">
            
             @if (session('status') === 'detail-updated' || session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-md" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>Informasi akun Anda telah berhasil diperbarui.</p>
                </div>
            @endif

            <section id="informasi-akun" class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200/80">
                <h2 class="text-2xl font-bold text-gray-800">Informasi Akun</h2>
                <p class="mt-1 text-gray-600">Ubah detail profil dan alamat email Anda.</p>
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <form action="{{ route('profil.update.detail') }}" method="POST" class="space-y-6">
                        @csrf
                        {{-- Field Name --}}
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700">Username</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                            @error('name') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Field Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700">Alamat Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                             @if (!$user->email_verified_at)
                                <p class="text-sm text-yellow-700 mt-2">Email Anda belum terverifikasi. <a href="#" class="font-semibold underline">Kirim ulang verifikasi.</a></p>
                             @else
                                <p class="text-sm text-green-700 mt-2">Email sudah terverifikasi.</p>
                             @endif
                             @error('email') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Field Kontak --}}
                        <div>
                            <label for="kontak" class="block text-sm font-semibold text-gray-700">Nomor Kontak (WhatsApp)</label>
                            <input type="text" id="kontak" name="kontak" value="{{ old('kontak', $user->kontak) }}" placeholder="Contoh: 081234567890" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                            @error('kontak') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-white text-sm font-semibold bg-gradient-to-r from-[#FFA72B] to-[#F16A00] hover:opacity-90 transition">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </section>
            
            <section id="keamanan" class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200/80">
                <h2 class="text-2xl font-bold text-gray-800">Keamanan</h2>
                <p class="mt-1 text-gray-600">Ubah password Anda secara berkala untuk menjaga keamanan akun.</p>
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <form action="{{ route('profil.update.password') }}" method="POST" class="space-y-6">
                        @csrf
                         {{-- Field Current Password --}}
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-gray-700">Password Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                            @error('current_password', 'updatePassword') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Field New Password --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password Baru</label>
                            <input type="password" id="password" name="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                             @error('password', 'updatePassword') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Field Confirm New Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-white text-sm font-semibold bg-gradient-to-r from-[#FFA72B] to-[#F16A00] hover:opacity-90 transition">Ubah Password</button>
                        </div>
                    </form>
                </div>
            </section>
            
            <section id="zona-berbahaya" class="bg-red-50 p-8 rounded-2xl border-2 border-dashed border-red-300">
                <h2 class="text-2xl font-bold text-red-800">Zona Berbahaya</h2>
                <p class="mt-1 text-red-700">Tindakan di bawah ini tidak dapat diurungkan. Mohon berhati-hati.</p>
                <div class="mt-6 border-t border-red-200 pt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-red-800">Hapus Akun Anda</h3>
                            <p class="text-sm text-red-700">Semua data pengaduan dan proposal Anda akan dihapus permanen.</p>
                        </div>
                        <button type="button" onclick="alert('Fitur ini belum diaktifkan.')" class="bg-red-600 text-white font-semibold text-sm px-5 py-2.5 rounded-lg hover:bg-red-700 transition">Hapus Akun</button>
                    </div>
                </div>
            </section>
        </div>

    </div>
</main>
@endsection