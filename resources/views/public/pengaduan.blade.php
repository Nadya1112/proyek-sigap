@extends('layouts.public')

@section('title', 'Pengaduan Masyarakat')

@section('content')

    {{-- ===== HERO (gradasi & glow) + STATISTIK ===== --}}
    <section class="relative overflow-hidden">
        <div class="relative"
            style="background: linear-gradient(135deg, #F7A623 0%, #FF7A00 45%, #F25C3B 70%, #F04949 100%);">
            {{-- glow kiri-atas --}}
            <div class="pointer-events-none absolute -top-6 -left-10 w-[360px] h-[360px] opacity-70"
                style="background: radial-gradient(closest-side, rgba(255,179,73,0.55) 0%, rgba(255,179,73,0.26) 34%, rgba(255,179,73,0.10) 60%, transparent 72%); filter: blur(2px);">
            </div>
            {{-- glow kanan-bawah --}}
            <div class="pointer-events-none absolute -bottom-10 -right-8 w-[340px] h-[340px] opacity-65"
                style="background: radial-gradient(closest-side, rgba(255,120,120,0.45) 0%, rgba(255,120,120,0.22) 35%, rgba(255,120,120,0.10) 58%, transparent 75%); filter: blur(2px);">
            </div>

            <div class="container mx-auto px-6 py-14 md:py-20 relative text-center text-white">
                <span
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 ring-1 ring-white/25">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                    Layanan Pengaduan
                </span>

                <h1
                    class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight drop-shadow-[0_1px_2px_rgba(0,0,0,0.12)]">
                    Pengaduan Masyarakat
                </h1>
                <p class="mt-4 text-base md:text-lg text-white/90 max-w-3xl mx-auto">
                    Sampaikan keluhan atau masukan Anda terkait PSU perumahan. Kami menindaklanjuti secara cepat,
                    transparan, dan bertanggung jawab.
                </p>

                {{-- STATISTIK: bg putih low-opacity + hover lift, caption di bawah --}}
                <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
                    @php
                        $statTotal = $total ?? 0;
                        $statDiterima = $diterima ?? 0;
                        $statProses = $proses ?? 0;
                        $statSelesai = $selesai ?? 0;
                    @endphp

                    <div
                        class="group rounded-[14px] px-8 py-6 text-center
                 bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)]
                 transition-all duration-300
                 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
                        <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $statTotal }}</p>
                        <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Total Pengaduan</p>
                    </div>

                    <div
                        class="group rounded-[14px] px-8 py-6 text-center
                 bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)]
                 transition-all duration-300
                 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
                        <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $statDiterima }}</p>
                        <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Sudah Diterima</p>
                    </div>

                    <div
                        class="group rounded-[14px] px-8 py-6 text-center
                 bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)]
                 transition-all duration-300
                 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
                        <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $statProses }}</p>
                        <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Dalam Proses</p>
                    </div>

                    <div
                        class="group rounded-[14px] px-8 py-6 text-center
                 bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)]
                 transition-all duration-300
                 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
                        <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $statSelesai }}</p>
                        <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Sudah Selesai</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FORM CARD ===== --}}
    <main class="py-16 lg:py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg border border-gray-100 p-8">

                @auth
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Formulir Pengaduan</h2>
                    <p class="text-gray-600 mb-6">Silakan isi detail pengaduan Anda di bawah ini.</p>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                            <p class="font-bold">Berhasil!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        <div>
                            <label for="nama_pelapor" class="form-label">Nama Lengkap Anda</label>
                            <input type="text" id="nama_pelapor" name="nama_pelapor" class="form-input"
                                value="{{ auth()->user()->name }}" required>
                            @error('nama_pelapor')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kontak_pelapor" class="form-label">Nomor Kontak (WA/Telepon)</label>
                            <input type="text" id="kontak_pelapor" name="kontak_pelapor" class="form-input"
                                value="{{ auth()->user()->kontak }}" required>
                            @error('kontak_pelapor')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="judul_pengaduan" class="form-label">Judul Pengaduan</label>
                            <input type="text" id="judul_pengaduan" name="judul_pengaduan" class="form-input"
                                value="{{ old('judul_pengaduan') }}" placeholder="Contoh: Kerusakan Jalan di Komplek A" required>
                            @error('judul_pengaduan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="isi_pengaduan" class="form-label">Isi Pengaduan</label>
                            <textarea id="isi_pengaduan" name="isi_pengaduan" rows="5" class="form-input" placeholder="Jelaskan detail pengaduan Anda..." required>{{ old('isi_pengaduan') }}</textarea>
                            @error('isi_pengaduan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="bukti_foto" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Unggah Bukti Foto <span class="font-normal text-gray-500">(Format: JPG, PNG, GIF. Maksimal
                                    2MB)</span>
                            </label>
                            <input id="bukti_foto" type="file" name="bukti_foto" accept="image/*" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50
                          file:mr-4 file:rounded-lg file:border-0 file:bg-[#FFA72B] file:px-4 file:py-2 file:text-white
                          hover:file:brightness-95 focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 transition" />
                            @error('bukti_foto')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="btn-gradient w-full">
                                <span class="relative z-10">Kirim Pengaduan</span>
                                <div class="btn-gradient-hover"></div>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center bg-orange-50/50 rounded-xl p-8 md:p-12 border border-orange-200/80">
                        {{-- Icon --}}
                        <div class="mx-auto w-16 h-16 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center shadow-lg mb-5">
                            <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H4.5a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-800">Silakan Masuk Terlebih Dahulu</h3>
                        <p class="text-gray-600 mt-2 mb-6 max-w-md mx-auto">
                            Anda harus memiliki akun dan masuk untuk dapat membuat pengaduan.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="{{ route('login') }}" class="btn-gradient rounded-lg px-8 py-3">
                                <span class="relative z-10 font-semibold">Masuk ke Akun</span>
                                <div class="btn-gradient-hover rounded-lg"></div>
                            </a>
                            <a href="{{ url('/register') }}"
                                class="font-semibold text-orange-600 hover:text-orange-700 transition">
                                Buat Akun Baru
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </main>

    <style>
        .page-header {
            background: linear-gradient(135deg, var(--sigap-yellow), var(--sigap-orange));
            padding: 4rem 0;
        }

        .page-header-title {
            color: white;
            font-size: 3rem;
            font-weight: 800;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .page-header-desc {
            color: white;
            opacity: 0.9;
            font-size: 1.25rem;
            max-width: 600px;
            margin: 1rem auto 0;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #4B5563;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.75rem;
            transition: all 0.2s ease-in-out;
        }

        .form-input:focus {
            border-color: var(--sigap-yellow);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
            outline: none;
        }
    </style>
@endsection
