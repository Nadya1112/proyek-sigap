@extends('layouts.public')

@section('title', 'Kelola Profil')

@section('content')

<main class="container mx-auto px-6 py-12 md:py-16">
    {{-- Header Halaman --}}
    <div class="text-center max-w-2xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 tracking-tight">Pengaturan Akun</h1>
        <p class="mt-3 text-lg text-gray-600">Kelola informasi akun, preferensi, dan keamanan Anda di satu tempat.</p>
    </div>

    {{-- Wrapper untuk Konten Terpusat dengan Alpine.js untuk Tab --}}
    <div x-data="{ tab: 'informasi' }" class="mt-10 max-w-4xl mx-auto">
        
        @if (session('status') === 'detail-updated' || session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-md" role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="w-6 h-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                    <div>
                        <p class="font-bold">Sukses!</p>
                        <p class="text-sm">Informasi akun Anda telah berhasil diperbarui.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Navigasi Tab --}}
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-6">
                <a href="#" @click.prevent="tab = 'informasi'"
                   :class="{ 'border-orange-500 text-orange-600': tab === 'informasi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'informasi' }"
                   class="whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-colors">
                    Informasi Akun
                </a>
                <a href="#" @click.prevent="tab = 'keamanan'"
                   :class="{ 'border-orange-500 text-orange-600': tab === 'keamanan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'keamanan' }"
                   class="whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-colors">
                    Keamanan
                </a>
                <a href="#" @click.prevent="tab = 'bahaya'"
                   :class="{ 'border-red-500 text-red-600': tab === 'bahaya', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'bahaya' }"
                   class="whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-colors">
                    Zona Berbahaya
                </a>
            </nav>
        </div>

        {{-- Konten Tab --}}
        <div class="mt-8">
            {{-- Tab 1: Informasi Akun --}}
            <div x-show="tab === 'informasi'" x-transition:enter="transition-all ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <form action="{{ route('profil.update.detail') }}" method="POST">
                    @csrf
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200/60 overflow-hidden">
                        <div class="p-6 md:p-8">
                             <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-orange-100 grid place-content-center">
                                    <svg class="w-6 h-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">Detail Profil</h2>
                                    <p class="mt-1 text-sm text-gray-600">Perbarui informasi personal Anda di sini.</p>
                                </div>
                            </div>
                            <div class="mt-6 space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                    @error('name') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                     @if (!$user->email_verified_at)
                                        <p class="text-xs text-yellow-700 mt-2">Email Anda belum terverifikasi. Jika Anda mengubahnya, Anda akan perlu verifikasi ulang.</p>
                                     @else
                                        <p class="text-xs text-green-700 mt-2">Email sudah terverifikasi.</p>
                                     @endif
                                     @error('email') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="kontak" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Kontak (WhatsApp)</label>
                                    <input type="text" id="kontak" name="kontak" value="{{ old('kontak', $user->kontak) }}" placeholder="Contoh: 081234567890" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                    @error('kontak') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 text-right">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-white text-sm font-semibold bg-gradient-to-r from-[#FFA72B] to-[#F16A00] hover:opacity-90 transition shadow-md hover:shadow-lg">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
            
            {{-- Tab 2: Keamanan --}}
            <div x-show="tab === 'keamanan'" x-transition:enter="transition-all ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                 <form action="{{ route('profil.update.password') }}" method="POST">
                    @csrf
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200/60 overflow-hidden">
                        <div class="p-6 md:p-8">
                             <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-blue-100 grid place-content-center">
                                    <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286zm0 13.036h.008v.008h-.008v-.008z" /></svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">Ubah Password</h2>
                                    <p class="mt-1 text-sm text-gray-600">Pastikan Anda menggunakan password yang kuat dan unik.</p>
                                </div>
                            </div>
                            <div class="mt-6 space-y-6">
                                <div>
                                    <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-1">Password Saat Ini</label>
                                    <input type="password" id="current_password" name="current_password" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                    @error('current_password', 'updatePassword') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                     @error('password', 'updatePassword') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 text-right">
                             <button type="submit" class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-white text-sm font-semibold bg-gradient-to-r from-[#FFA72B] to-[#F16A00] hover:opacity-90 transition shadow-md hover:shadow-lg">Perbarui Password</button>
                        </div>
                    </div>
                </form>
            </div>
            
            {{-- Tab 3: Zona Berbahaya --}}
            <div x-show="tab === 'bahaya'" x-transition:enter="transition-all ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200/60 overflow-hidden">
                     <div class="p-6 md:p-8">
                         <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-red-100 grid place-content-center">
                               <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Hapus Akun</h2>
                                <p class="mt-1 text-sm text-gray-600">Setelah akun Anda dihapus, semua data akan hilang secara permanen.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                        <p class="text-sm text-red-700 font-semibold">Tindakan ini tidak dapat diurungkan.</p>
                        <button type="button" onclick="alert('Fitur hapus akun akan segera tersedia.')" class="bg-red-600 text-white font-semibold text-sm px-5 py-2.5 rounded-lg hover:bg-red-700 transition shadow-md hover:shadow-lg">Hapus Akun Permanen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection