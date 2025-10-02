@extends('layouts.public')

@section('title', 'Kelola Profil')

@section('content')

<main class="container mx-auto px-6 py-12 md:py-16">
    {{-- Header Halaman --}}
    <div class="text-center max-w-2xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 tracking-tight">Pengaturan Akun</h1>
        <p class="mt-3 text-lg text-gray-600">Kelola informasi akun, preferensi, dan keamanan Anda di satu tempat.</p>
    </div>

    {{-- Wrapper dengan Alpine.js yang sudah diperbarui --}}
    {{-- Atribut x-data sekarang memeriksa apakah ada error, dan mengaktifkan tab + form delete jika ya --}}
    <div x-data="{ tab: '{{ $errors->has('password_confirm') ? 'keamanan' : ($token ? 'keamanan' : session('active_tab', 'informasi')) }}' }" class="mt-10 max-w-4xl mx-auto">
        
        {{-- Notifikasi --}}
        @if (session('status'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                class="mb-6 @if(session('status') === 'password-link-sent') bg-blue-50 border-blue-500 text-blue-800 @else bg-green-50 border-green-500 text-green-800 @endif border-l-4 p-4 rounded-lg shadow-md" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="w-6 h-6 @if(session('status') === 'password-link-sent') text-blue-500 @else text-green-500 @endif mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="font-bold">
                            @if(session('status') === 'password-link-sent')
                                Link Terkirim!
                            @else
                                Sukses!
                            @endif
                        </p>
                        <p class="text-sm">
                            @if(session('status') === 'password-link-sent')
                                Kami telah mengirimkan link reset password ke email Anda. Silakan periksa inbox Anda.
                            @elseif(session('status') === 'detail-updated')
                                Informasi akun Anda telah berhasil diperbarui.
                            @elseif(session('status') === 'password-updated')
                                Password Anda telah berhasil diperbarui.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif


        {{-- Navigasi Tab --}}
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-6">
                <a href="#" @click.prevent="tab = 'informasi'" :class="{ 'border-orange-500 text-orange-600': tab === 'informasi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'informasi' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-colors">Informasi Akun</a>
                <a href="#" @click.prevent="tab = 'keamanan'" :class="{ 'border-orange-500 text-orange-600': tab === 'keamanan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'keamanan' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-colors">Keamanan</a>
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
            
            {{-- ======================================================= --}}
            {{-- === TAB 2: KEAMANAN (INI BAGIAN YANG DIPERBAIKI) === --}}
            {{-- ======================================================= --}}
            <div x-show="tab === 'keamanan'" x-transition:enter="transition-all ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                
                {{-- Tambahkan div pembungkus dengan jarak --}}
                <div class="space-y-8">

                    {{-- Card 1: Verifikasi Ganti Password --}}
                    <div>
                        {{-- TAMPILKAN FORM INI JIKA ADA TOKEN DARI EMAIL --}}
                        @if (isset($token))
                        <form action="{{ route('profil.keamanan.reset') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="bg-white rounded-2xl shadow-xl border border-gray-200/60 overflow-hidden">
                                <div class="p-6 md:p-8">
                                     <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-blue-100 grid place-content-center">
                                            <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-bold text-gray-800">Buat Password Baru</h2>
                                            <p class="mt-1 text-sm text-gray-600">Pastikan Anda menggunakan password yang kuat dan unik.</p>
                                        </div>
                                    </div>
                                    <div class="mt-6 space-y-6">
                                        <div>
                                            <label for="email_reset" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                            <input type="email" id="email_reset" name="email" value="{{ old('email', $email) }}" readonly class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-500">
                                            @error('email') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                                            <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                            @error('password') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                            <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-6 py-4 text-right">
                                     <button type="submit" class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-white text-sm font-semibold bg-gradient-to-r from-[#FFA72B] to-[#F16A00] hover:opacity-90 transition shadow-md hover:shadow-lg">Simpan Password Baru</button>
                                </div>
                            </div>
                        </form>

                        {{-- TAMPILKAN FORM INI JIKA TIDAK ADA TOKEN (TAMPILAN DEFAULT) --}}
                        @else
                         <form action="{{ route('profil.keamanan.kirim-link') }}" method="POST">
                            @csrf
                            <div class="bg-white rounded-2xl shadow-xl border border-gray-200/60 overflow-hidden">
                                <div class="p-6 md:p-8">
                                     <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-blue-100 grid place-content-center">
                                            <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-bold text-gray-800">Verifikasi Ganti Password</h2>
                                            <p class="mt-1 text-sm text-gray-600">Kami akan mengirimkan link ke email Anda untuk melanjutkan proses ganti password.</p>
                                        </div>
                                    </div>
                                    <div class="mt-6">
                                        <label for="email_request" class="block text-sm font-semibold text-gray-700 mb-1">Email Anda</label>
                                        <input type="email" id="email_request" name="email" value="{{ $user->email }}" readonly class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-500">
                                        @error('email') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-6 py-4 text-right">
                                     <button type="submit" class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-white text-sm font-semibold bg-gradient-to-r from-[#FFA72B] to-[#F16A00] hover:opacity-90 transition shadow-md hover:shadow-lg">Kirim Link Reset</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>

                    {{-- Card 2: Hapus Akun --}}
                    <div>
                        {{-- ================================================================================= --}}
                        {{-- === PERBAIKAN UTAMA ADA DI BARIS x-data DI BAWAH INI === --}}
                        {{-- ================================================================================= --}}
                        <div x-data="{ showDeleteConfirm: {{ $errors->has('password_confirm') ? 'true' : 'false' }} }" class="bg-white rounded-2xl shadow-xl border border-gray-200/60 overflow-hidden">
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
                            
                            <div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" style="display: none;">
                                <form action="{{ route('profil.destroy') }}" method="POST" class="p-6 md:p-8 border-t border-gray-200">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <p class="text-sm text-center font-semibold text-gray-700">Untuk melanjutkan, silakan masukkan password Anda saat ini.</p>
                                    <div class="mt-4 max-w-sm mx-auto">
                                        <label for="password_confirm_inline" class="sr-only">Password</label>
                                        <input type="password" name="password_confirm" id="password_confirm_inline" placeholder="Masukkan password Anda" class="w-full px-4 py-2 text-center border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500" required>
                                        @error('password_confirm') <p class="mt-2 text-xs text-red-600 text-center">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="mt-5 flex justify-center gap-3">
                                        <button type="button" @click="showDeleteConfirm = false" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                                        <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">Ya, Hapus Akun Saya</button>
                                    </div>
                                </form>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                               <p class="text-sm text-red-700 font-semibold">Tindakan ini tidak dapat diurungkan.</p>
                               <button type="button" @click="showDeleteConfirm = !showDeleteConfirm" class="bg-red-600 text-white font-semibold text-sm px-5 py-2.5 rounded-lg hover:bg-red-700 transition shadow-md hover:shadow-lg">Hapus Akun Permanen</button>
                           </div>
                        </div>
                    </div>
                    
                </div>
            </div>


                </div>
            </div>
    </div>

</main>
@endsection